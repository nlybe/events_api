<?php

namespace Events\API;

use ElggObject;
use ElggBatch;
use DateTime;
use DateTimeZone;
use vcalendar;

/**
 * Event object
 *
 * @property integer $start_date
 * @property integer $end_date
 * @property integer $start_time
 * @property integer $end_time
 * @property string  $timezone
 * @property integer $start_timestamp
 * @property integer $end_timestamp
 * @property integer $end_delta
 * @property bool    $all_day
 * @property bool    $repeat
 * @property integer $repeat_end_after
 * @property integer $repeat_end_on
 * @property string  $repeat_frequency
 * @property string  $repeat_end_type
 * @property string  $repeat_monthly_by
 * @property integer $repeat_end_timestamp
 * @property mixed   $repeat_weekly_days
 * @property mixed   $reminder
 * @property mixed   $cancelled_instance
 */
class Event extends ElggObject {

	const SUBTYPE = 'event';

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	/**
	 * Returns events title
	 * @return string
	 */
	public function getDisplayName() {
		return ($this->title) ? : elgg_echo('events:edit:title:placeholder');
	}

	/**
	 * Returns canonical URL with instance start time added as query element
	 * 
	 * @param int $start_timestamp Start time of the instance
	 * @param int $calendar_guid   GUID of the calendar in context
	 * @return string
	 */
	public function getURL($start_timestamp = 0, $calendar_guid = 0) {
		if (!$start_timestamp) {
			$start_timestamp = $this->getNextOccurrence();
		}
		$url = parent::getURL();
		return elgg_http_add_url_query_elements($url, array_filter(array(
			'ts' => $start_timestamp,
			'calendar' => $calendar_guid,
		)));
	}

	/**
	 * Get all calendars this event is on
	 * 
	 * @param array custom parameters to override the default egef
	 * @param bool $public - limit to only personal public calendars?
	 */
	public function getCalendars($params = array(), $public = false) {
		$defaults = array(
			'type' => 'object',
			'subtype' => 'calendar',
			'relationship' => Calendar::EVENT_CALENDAR_RELATIONSHIP,
			'relationship_guid' => $this->guid,
			'limit' => false
		);

		$options = array_merge($defaults, $params);

		if ($public) {
			$options['metadata_name_value_pairs'][] = array(
				'name' => '__public_calendar',
				'value' => true
			);
		}

		if ($options['count']) {
			return elgg_get_entities_from_relationship($options);
		}

		return new ElggBatch('elgg_get_entities_from_relationship', $options);
	}

	/**
	 * Perform a move action with calculated parameters
	 * 
	 * @param array $params New event parameters
	 * @return boolean
	 */
	public function move($params) {
		//update the event
		$this->all_day = elgg_extract('all_day', $params, false);
		$this->start_timestamp = $params['new_start_timestamp'];
		$this->end_timestamp = $params['new_end_timestamp'];
		$this->start_date = $params['new_start_date'];
		$this->end_date = $params['new_end_date'];
		$this->start_time = $params['new_start_time'];
		$this->end_time = $params['new_end_time'];
		$this->end_delta = $params['new_end_timestamp'] - $params['new_start_timestamp']; // how long this is in seconds
		
		$this->repeat_end_timestamp = $this->calculateRepeatEndTimestamp();
		
		// rebuild reminders for the next 2 days
		$time = time();
		$this->removeReminders(null, null, true); // remove all reminders
		$this->buildReminders($time, $time + (Util::SECONDS_IN_A_DAY * 2));

		return true;
	}

	/**
	 * Extends event duration
	 *
	 * @param array $params New end parameters
	 * @return boolean
	 */
	public function resize($params) {
		//update the event
		$this->end_timestamp = $params['new_end_timestamp'];
		$this->end_date = $params['new_end_date'];
		$this->end_time = $params['new_end_time'];
		$this->end_delta = $params['new_end_timestamp'] - $this->getStartTimestamp(); // how long this is in seconds

		$this->repeat_end_timestamp = $this->calculateRepeatEndTimestamp();
		return true;
	}

	/**
	 * Adds an event to a calendar
	 *
	 * @param int $calendar_guid GUID of the calendar
	 * @return boolean
	 */
	public function addToCalendar($calendar_guid) {
		$calendar = get_entity($calendar_guid);
		if ($calendar instanceof Calendar) {
			$calendar->addEvent($this);
			return true;
		}
		return false;
	}

	/**
	 * Removes an event from a calendar
	 *
	 * @param int $calendar_guid GUID of the calendar
	 * @return boolean
	 */
	public function removeFromCalendar($calendar_guid) {
		$calendar = get_entity($calendar_guid);
		if ($calendar instanceof Calendar) {
			$calendar->removeEvent($this);
			return true;
		}

		return false;
	}

	/**
	 * Checks if the event is recurring
	 * @return boolean
	 */
	public function isRecurring() {
		return (bool) $this->repeat;
	}

	/**
	 * Returns integer value of start_timestamp metastring
	 * @return int
	 */
	public function getStartTimestamp() {
		return (int) $this->start_timestamp;
	}

	/**
	 * Returns integer value of end_timestamp metastring
	 * @return int
	 */
	public function getEndTimestamp() {
		return (int) $this->end_timestamp;
	}

	/**
	 * Returns event timezone
	 * @return string
	 */
	public function getTimezone() {
		return (Util::isValidTimezone($this->timezone)) ? $this->timezone : Util::UTC;
	}

	/**
	 * Returns host
	 * @return \ElggEntity
	 */
	public function getHost() {
		$container = $this->getOwnerEntity();
		return ($container) ? : elgg_get_site_entity();
	}

	/**
	 * Checks if it's an all day event
	 * @return bool
	 */
	public function isAllDay() {
		return (bool) $this->all_day;
	}

	/**
	 * Calculates parameters for a move action
	 * 
	 * @param int  $day_delta    Positive or negative number of days from the original event day
	 * @param int  $minute_delta Position or negative number of minutes from the origin event time
	 * @param bool $all_day      All day event?
	 * @return array
	 */
	public function getMoveParams($day_delta, $minute_delta, $all_day = false) {
		// calculate new dates
		$start_timestamp = $this->getStartTimestamp();
		$end_timestamp = $this->getEndTimestamp();

		$time_diff = $end_timestamp - $start_timestamp;
		$new_start_timestamp = $start_timestamp + ($day_delta * Util::SECONDS_IN_A_DAY) + ($minute_delta * Util::SECONDS_IN_A_MINUTE);
		$new_end_timestamp = $new_start_timestamp + $time_diff;

		$params = array(
			'entity' => $this,
			'new_start_timestamp' => $new_start_timestamp,
			'new_end_timestamp' => $new_end_timestamp,
			'new_start_date' => date('Y-m-d', $new_start_timestamp),
			'new_end_date' => date('Y-m-d', $new_end_timestamp),
			'new_start_time' => date('g:ia', $new_start_timestamp),
			'new_end_time' => date('g:ia', $new_end_timestamp),
			'all_day' => $all_day
		);

		return $params;
	}

	/**
	 * Calculates parameters for the resize action
	 *
	 * @param int $day_delta    Positive or negative number of days from the original event end
	 * @param int $minute_delta Positive or negative number of minutes from the original event end
	 * @return array
	 */
	public function getResizeParams($day_delta = 0, $minute_delta = 0) {
		// calculate new dates
		$end_timestamp = $this->getEndTimestamp();

		$new_end_timestamp = $end_timestamp + ($day_delta * Util::SECONDS_IN_A_DAY) + ($minute_delta * Util::SECONDS_IN_A_MINUTE);

		$params = array(
			'entity' => $this,
			'new_end_timestamp' => $new_end_timestamp,
			'new_end_date' => date('Y-m-d', $new_end_timestamp),
			'new_end_time' => date('g:ia', $new_end_timestamp)
		);

		return $params;
	}

	/**
	 * Returns an array of start times for an event within a given timestamp range
	 * Note - assumes timestamp range is in increments of days
	 *
	 * @param int $startime Range start UNIX timestamp
	 * @param int $endtime  Range end UNIX timestamp
	 * @return array
	 */
	public function getStartTimes($starttime = 0, $endtime = 0, $tz = null) {
		if (!Util::isValidTimezone($tz)) {
			$tz = Util::getClientTimezone();
		}

		if (!$this->isRecurring()) {
			return array($this->getStartTimestamp());
		}

		$start_times = array();

		$test_day = ($this->getStartTimestamp() > $starttime) ? $this->getStartTimestamp() : $starttime;

		// iterate through each day of our range and see if this event shows up on any of those days
		while ($test_day < $endtime) {

			$shows = false;

			$offset_on_test_day = Util::getOffset($test_day, Util::UTC, $this->getTimezone());

			// next increment
			$next_test_day = $test_day + Util::SECONDS_IN_A_DAY;

			// event has no more occurrences after this day
			if ($this->repeat_end_timestamp && $this->repeat_end_timestamp < $test_day) {
				break;
			}

			switch ($this->repeat_frequency) {
				case Util::FREQUENCY_DAILY:
					$shows = true;
					break;

				case Util::FREQUENCY_WEEKDAY:
					$repeat_weekly_days = array(Util::SATURDAY, Util::SUNDAY);
					foreach ($repeat_weekly_days as $key => $day) {
						$repeat_weekly_days[$key] = Util::getDayOfWeek(strtotime($day, $test_day) - $offset_on_test_day);
					}
					$D = Util::getDayOfWeek($test_day);
					$shows = !in_array($D, $repeat_weekly_days);
					break;

				case Util::FREQUENCY_WEEKDAY_ODD:
					$repeat_weekly_days = array(Util::MONDAY, Util::WEDNESDAY, Util::FRIDAY);
					foreach ($repeat_weekly_days as $key => $day) {
						$repeat_weekly_days[$key] = Util::getDayOfWeek(strtotime($day, $test_day) - $offset_on_test_day);
					}
					$D = Util::getDayOfWeek($test_day);
					$shows = in_array($D, $repeat_weekly_days);
					break;

				case Util::FREQUENCY_WEEKDAY_EVEN:
					$repeat_weekly_days = array(Util::TUESDAY, Util::THURSDAY);
					foreach ($repeat_weekly_days as $key => $day) {
						$repeat_weekly_days[$key] = Util::getDayOfWeek(strtotime($day, $test_day) - $offset_on_test_day);
					}
					$D = Util::getDayOfWeek($test_day);
					$shows = in_array($D, $repeat_weekly_days);
					break;

				case Util::FREQUENCY_WEEKLY:
					$repeat_weekly_days = $this->repeat_weekly_days;
					if (!$repeat_weekly_days) {
						$repeat_weekly_days = Util::getDayOfWeek($this->getStartTimestamp());
					}
					if (!is_array($repeat_weekly_days)) {
						$repeat_weekly_days = array($repeat_weekly_days);
					}
					$D = Util::getDayOfWeek($test_day);
					foreach ($repeat_weekly_days as $key => $day) {
						// Monday in Sydney can still be Sunday at UTC
						$repeat_weekly_days[$key] = Util::getDayOfWeek(strtotime($day, $test_day) - $offset_on_test_day);
					}
					$shows = in_array($D, $repeat_weekly_days);
					break;

				case Util::FREQUENCY_MONTHLY:
					if ($this->repeat_monthly_by == Util::REPEAT_MONTHLY_BY_DAY_OF_WEEK) {
						$shows = Util::isOnSameWeekDayOfMonth($test_day, $this->getStartTimestamp());
						if ($shows) {
							// we can skip 4 weeks
							$next_test_day = strtotime('+28 days', $test_day);
						}
					} else {
						$shows = Util::isOnSameDayOfMonth($test_day, $this->getStartTimestamp());
						if ($shows) {
							// we can skip a month
							$next_test_day = strtotime('+1 month', $test_day);
						}
					}
					break;

				case Util::FREQUENCY_YEARLY:
					$shows = Util::isOnSameDayOfYear($test_day, $this->getStartTimestamp());
					if ($shows) {
						// we can skip a year
						$next_test_day = strtotime('+1 year', $test_day);
					}
					break;
			}

			if ($shows) {
				$occurrence = (int) Util::getTimeOfDay($this->getStartTimestamp(), $test_day);
				if ($occurrence >= $starttime && $occurrence <= $endtime) {
					// events may show on a start or end day, but not fall in the time range
					array_push($start_times, $occurrence);
				}
			}

			$test_day = $next_test_day;
		}

		$cancelled = $this->cancelled_instance;
		if (!$cancelled) {
			$cancelled = array();
		}
		if (!is_array($cancelled)) {
			$cancelled = array($cancelled);
		}

		return array_diff($start_times, $cancelled);
	}

	/**
	 * Calculates and returns the last timestamp for event recurrences
	 * @return int
	 */
	public function calculateRepeatEndTimestamp() {

		// determine when it actually stops repeating in terms of timestamp
		switch ($this->repeat_end_type) {

			case Util::REPEAT_END_ON:
				$repeat_end_timestamp = strtotime($this->repeat_end_on);
				if ($repeat_end_timestamp === false) {
					$repeat_end_timestamp = 0; //@TODO - what else could we do here?
				}
				$return = $repeat_end_timestamp;
				break;
			case Util::REPEAT_END_AFTER:
				$return = $this->calculateEndAfterTimestamp($this->repeat_end_after);
				break;
			case Util::REPEAT_END_NEVER :
				$return = 0;
				break;
			default :
				if ($this->repeat) {
					$return = 0;
				}
				else {
					$return = $this->getStartTimestamp();
				}
			break;
		}
		
		if ($return && $return < $this->getEndTimestamp()) {
			$return = $this->getEndTimestamp();
		}
		
		return $return;
	}

	/**
	 * Calculates the end (or start) timestamp of the last event in a sequence of occurrences
	 *
	 * @param int  $occurrences    Max number of occurrences
	 * @param int  $from_timestamp Initial time to calculate from (defaults to event start time)
	 * @param bool $at_event_end   If true, will return the timestamp of the event end, otherwise event start
	 * @return int
	 */
	public function calculateEndAfterTimestamp($occurrences = 1, $from_timestamp = null, $at_event_end = true) {

		$occurrences = (int) $occurrences;

		$start_timestamp = $this->getStartTimestamp();

		$start_day = $start_timestamp;
		$test_day = ($start_timestamp > $from_timestamp) ? $start_timestamp : $from_timestamp;

		while ($occurrences > 0) {

			$shows = false;
			$offset_on_test_day = Util::getOffset($test_day, Util::UTC, $this->getTimezone());

			// next increment
			$next_test_day = $test_day + Util::SECONDS_IN_A_DAY;

			switch ($this->repeat_frequency) {
				default:
					$occurrences = 0;
					break;

				case Util::FREQUENCY_DAILY:
					$shows = true;
					break;

				case Util::FREQUENCY_WEEKDAY:
					$repeat_weekly_days = array(Util::SATURDAY, Util::SUNDAY);
					foreach ($repeat_weekly_days as $key => $day) {
						$repeat_weekly_days[$key] = Util::getDayOfWeek(strtotime($day, $test_day) - $offset_on_test_day);
					}
					$D = Util::getDayOfWeek($test_day);
					$shows = !in_array($D, $repeat_weekly_days);
					break;

				case Util::FREQUENCY_WEEKDAY_ODD:
					$repeat_weekly_days = array(Util::MONDAY, Util::WEDNESDAY, Util::FRIDAY);
					foreach ($repeat_weekly_days as $key => $day) {
						$repeat_weekly_days[$key] = Util::getDayOfWeek(strtotime($day, $test_day) - $offset_on_test_day);
					}
					$D = Util::getDayOfWeek($test_day);
					$shows = in_array($D, $repeat_weekly_days);
					break;

				case Util::FREQUENCY_WEEKDAY_EVEN:
					$repeat_weekly_days = array(Util::TUESDAY, Util::THURSDAY);
					foreach ($repeat_weekly_days as $key => $day) {
						$repeat_weekly_days[$key] = Util::getDayOfWeek(strtotime($day, $test_day) - $offset_on_test_day);
					}
					$D = Util::getDayOfWeek($test_day);
					$shows = in_array($D, $repeat_weekly_days);
					break;

				case Util::FREQUENCY_WEEKLY:
					$repeat_weekly_days = $this->repeat_weekly_days;
					if (!$repeat_weekly_days) {
						$repeat_weekly_days = Util::getDayOfWeek($this->getStartTimestamp());
					}
					if (!is_array($repeat_weekly_days)) {
						$repeat_weekly_days = array($repeat_weekly_days);
					}
					$D = Util::getDayOfWeek($test_day);
					foreach ($repeat_weekly_days as $key => $day) {
						// use offsets
						// Monday in Sydney can still be Sunday at UTC
						$repeat_weekly_days[$key] = Util::getDayOfWeek(strtotime($day, $test_day) - $offset_on_test_day);
					}
					$shows = in_array($D, $repeat_weekly_days);
					break;

				case Util::FREQUENCY_MONTHLY:
					if ($this->repeat_monthly_by == Util::REPEAT_MONTHLY_BY_DAY_OF_WEEK) {
						$shows = Util::isOnSameWeekDayOfMonth($test_day, $this->getStartTimestamp());
						if ($shows) {
							// we can skip 4 weeks
							$next_test_day = strtotime('+28 days', $test_day);
						}
					} else {
						$shows = Util::isOnSameDayOfMonth($test_day, $this->getStartTimestamp());
						if ($shows) {
							// we can skip a month
							$next_test_day = strtotime('+1 month', $test_day);
						}
					}
					break;

				case Util::FREQUENCY_YEARLY:
					$shows = Util::isOnSameDayOfYear($test_day, $this->getStartTimestamp());
					if ($shows) {
						// we can skip a year
						$next_test_day = strtotime('+1 year', $test_day);
					}
					break;
			}

			if ($shows) {
				$occurrences--;
				$start_timestamp = (int) Util::getTimeOfDay($this->getStartTimestamp(), $test_day);
			}

			$test_day = $next_test_day;
		}

		return ($at_event_end) ? $start_timestamp + $this->end_delta : $start_timestamp;
	}

	/**
	 * Returns the start timestamp of the next event occurence
	 * Returns false if there are no future occurrences
	 *
	 * @param int $after_timestamp Find next occurrence
	 * @return int|false
	 */
	public function getNextOccurrence($after_timestamp = null) {
		if ($after_timestamp === null) {
			$after_timestamp = time();
		}
		
		$after_timestamp = (int) $after_timestamp;

		$next = false;
		if ($this->isRecurring()) {
			$next = $this->calculateEndAfterTimestamp(1, $after_timestamp, false);
		} else if ($after_timestamp < $this->getStartTimestamp()) {
			$next = $this->getStartTimestamp();
		}

		if ($this->repeat_end_timestamp && $this->repeat_end_timestamp < $next) {
			return false;
		}

		return $next;
	}
	
	
	/**
	 * Returns the timestamp of the previous event occurence
	 * Returns false if there are no past occurrences
	 *
	 * @param int $before_timestamp Find previous occurrence
	 * @return int|false
	 */
	public function getLastOccurrence($before_timestamp = null) {
		if ($before_timestamp === null) {
			$before_timestamp = time();
		}
		
		$before_timestamp = (int) $before_timestamp;
		
		$prev = false;
		if ($this->isRecurring()) {
			$starttimes = $this->getStartTimes($this->getStartTimestamp(), $before_timestamp);
			
			if ($starttimes) {
				$prev = end($starttimes);
			}
		}
		else {
			$prev = $this->getStartTimestamp();
			
			if ($prev > $before_timestamp) {
				$prev = false; // there is no occurrence before the timestamp
			}
		}
		
		return $prev;
	}

	/**
	 * Validates that one of the event occurrences starts at the provided timestamp
	 * 
	 * @param int $start_timestamp Timestamp to validate
	 * @return bool
	 */
	public function isValidStartTime($start_timestamp) {
		return $start_timestamp == $this->getNextOccurrence($start_timestamp - 1);
	}

	/**
	 * Checks if the event has reminders
	 * @return bool
	 */
	public function hasReminders() {
		return (!empty($this->reminder));
	}

	/**
	 * Creates reminder annotations for this event
	 * 
	 * @param type $starttime
	 * @param type $endtime
	 * @return boolean
	 */
	public function buildReminders($starttime, $endtime) {
		if (!$this->hasReminders()) {
			return true;
		}
		
		$starttime = sanitize_int($starttime);
		$endtime = sanitize_int($endtime);
		
		// first delete any existing reminders for this time period
		$this->removeReminders($starttime, $endtime);
		
		$reminders = elgg_get_metadata(array(
			'guid' => $this->guid,
			'metadata_name' => 'reminder',
			'limit' => false
		));
				
		// create reminders in this time period
		$starttimes = $this->getStartTimes($starttime, $endtime);
		foreach ($starttimes as $s) {
			foreach ($reminders as $r) {
				$reminder = $s - $r->value;
				if ($reminder < time()) {
					continue; // already passed
				}
				
				$this->annotate('reminder', $s - $r->value, ACCESS_PUBLIC);
			}
		}
		return true;
	}
	
	
	/**
	 * delete reminder annotations for a time period
	 * use the $all override to delete all reminder annotations period
	 * 
	 * @param type $starttime
	 * @param type $endtime
	 * @param type $all
	 */
	public function removeReminders($starttime, $endtime, $all = false) {
		$starttime = sanitize_int($starttime);
		$endtime = sanitize_int($endtime);
		
		$options = array(
			'guid' => $this->guid,
			'annotation_names' => array('reminder'),
			'limit' => false
		);
		
		if (!$all) {
			$options['wheres'] = "CAST(v.string as SIGNED) BETWEEN {$starttime} AND {$endtime}";
		}
		
		return elgg_delete_annotations($options);
	}
	
	
	public function getRecurringDescription($viewer = null) {		
		if (!$this->isRecurring()) {
			return elgg_echo('events_ui:repeat:once');
		}
		if ($viewer === null) {
			$viewer = elgg_get_logged_in_user_entity();
		}
		
		$c_timezone = Util::getClientTimezone($viewer);
		$timezone = $this->timezone ? $this->timezone : Util::UTC;
		$dt_start = new DateTime("@{$this->start_timestamp}", new DateTimeZone($timezone));
		$dt_start->setTimezone(new DateTimeZone($c_timezone));
		
		$description = elgg_echo('events_ui:repeat:' . $this->repeat_frequency);
		
		switch ($this->repeat_frequency) {
			case 'monthly':
				if ($this->repeat_monthly_by == 'day_of_month') {
					$description .= elgg_echo('repeat_ui:repeat_monthly_by:day_of_month:date', array($dt_start->format('jS')));
				}
				else {
					$day = $dt_start->format('j');
					$weeknum = ceil($day / 7);
					$weekday = elgg_echo('events:wd:' . $dt_start->format('D'));
					$suffix = str_replace($weeknum, '', date('jS', mktime(12, 0, 0, 04, $weeknum, 2015)));
					$description .= elgg_echo('repeat_ui:repeat_monthly_by:day_of_month:weekday', array($weeknum.$suffix, $weekday));
				}
				break;
			
			case 'weekly':
				$weekdays = array_map(function($val) { return elgg_echo('events:wd:'.$val); }, (array) $this->repeat_weekly_days);
				$description .= elgg_echo('repeat_ui:repeat:weekly:weekday', array(implode(', ', $weekdays)));
				break;
		}
		
		return $description;
	}
	
	/**
	 * 
	 * @param string $base_url
	 * @param array $params
	 * @return string the url
	 */
	public function getIcalURL($base_url = '', array $params = array()) {

		$params['view'] = 'ical';

		$url = elgg_http_add_url_query_elements($base_url, $params);
		return elgg_normalize_url($url);
	}
	
	/**
	 * Returns an iCal feed for this event
	 *
	 * @param int    $starttime Range start timestamp
	 * @param int    $endtime   Range end timestamp
	 * @param string $filename  Filename used for output file
	 * @return string
	 */
	public function toIcal($starttime = null, $endtime = null, $filename = 'event.ics') {

		if (is_null($starttime)) {
			$starttime = $this->getNextOccurrence();
		}

		$eventInstance = new EventInstance($this, $starttime); //$this->getAllEventInstances($starttime, $endtime, true, 'ical');
		$instance = $eventInstance->export('ical');
		
		$config = array(
			'unique_id' => $this->guid,
			// setting these explicitly until icalcreator bug #14 is solved
			'allowEmpty' => true,
			'nl' => "\r\n",
			'format' => 'iCal',
			'delimiter' => DIRECTORY_SEPARATOR,
			'filename' => $filename, // this is last until #14 is solved
		);

		$v = new vcalendar($config);
		$v->setProperty('method', 'PUBLISH');
		$v->setProperty("X-WR-CALNAME", implode(' - ', array(elgg_get_config('sitename'), $this->getDisplayName())));
		$v->setProperty("X-WR-CALDESC", strip_tags($this->description));
		$v->setProperty("X-WR-TIMEZONE", Util::UTC);

		$e = & $v->newComponent('vevent');

		$organizer = elgg_extract('organizer', $instance, array());
		unset($instance['organizer']);

		$reminders = elgg_extract('reminders', $instance, array());
		unset($instance['reminders']);

		foreach ($instance as $property => $value) {
			$e->setProperty($property, $value);
		}

		if (!empty($organizer)) {
			if (is_email_address($organizer)) {
				$e->setProperty('organizer', $organizer);
			} else {
				$e->setProperty('organizer', elgg_get_site_entity()->email, array(
					'CN' => $organizer,
				));
			}
		}

		if (!empty($reminders)) {
			foreach ($reminders as $reminder) {
				$a = & $e->newComponent('valarm');
				$a->setProperty('action', 'DISPLAY');
				$a->setProperty('trigger', "-PT{$reminder}S");
			}
		}

		$v->returnCalendar();
	}
}
