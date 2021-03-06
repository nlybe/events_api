<?php

$english = array(
	'events:edit:title:placeholder' => 'Untitled Event',
	'events:error:start_end_date' => "End date/time must be after the start date/time",
	'events:error:start_end_date:invalid_format' => "Invalid format for date/time",
	'events:error:save' => "Could not save event information",
	'events:error:container_permissions' => "You do not have sufficient permissions for this action",
	'events:success:save' => "Event Saved",
	'events:error:invalid:guid' => "Invalid Event",
	'events:error:invalid:deltas' => "Invalid move parameters",
	'event_api:event:updated' => "Your event has been updated",
	'events:success:deleted' => "Event has been deleted",
	'events:error:permissions' => "You don't have permission to change this event",
	'events:error:public_calendar_delete' => 'You are trying to delete a public calendar,
which is not allowed.
		For better access control,
use visibility settings on your custom calendars and individual events',

	'events:error:no_public_for_orphans' => 'We can not find a public calendar to move the events to',
	
	'events:wd:Mon' => 'Monday',
	'events:wd:Tue' => 'Tuesday',
	'events:wd:Wed' => 'Wednesday',
	'events:wd:Thu' => 'Thursday',
	'events:wd:Fri' => 'Friday',
	'events:wd:Sat' => 'Saturday',
	'events:wd:Sun' => 'Sunday',

	'events:calendar' => 'Calendar',
	'events:calendar:display_name' => '%s\'s Calendar',
	'events:calendar:edit:title:placeholder' => 'Untitled Calendar',
	'events:calendar:edit:success' => 'Calendar has been saved',
	'events:calendar:edit:error' => 'An error occurred while saving the calendar',
	'events:calendar:error:invalid:guid' => "Invalid Calendar",
	'events:calendar:delete:success' => "Calendar has been deleted and events moved to your default calendar",
	'events:calendar:delete:error' => "Calendar could not be deleted",
	'events:calendar:add_event:error:invalid_guid' => "Invalid Calendar or Event",
	'events:calendar:add_event:error:noaccess' => "You are not allowed to add events to this calendar",
	'events:calendar:add_event:already_on' => 'Event is already on the calendar',
	'events:calendar:add_event:success' => 'Event has been added to the calendar',
	'events:calendar:add_event:error' => 'Event could not be added to your calendar',

	'events:cancel:success' => 'Event has been cancelled',
	
	/**
	 * Repeating event strings
	 */
	'events_ui:repeat' => "Repeat",
	'events_ui:repeats' => "Repeats",
	'events_ui:repeat:once' => "Non-repeating",
	'events_ui:repeat:daily' => 'Daily',
	'events_ui:repeat:weekday' => "Every Weekday (Monday - Friday)",
	'events_ui:repeat:dailymwf' => "Every Monday, Wednesday, and Friday",
	'events_ui:repeat:dailytt' => "Every Tuesday and Thursday",
	'events_ui:repeat:weekly' => "Weekly",
	'events_ui:repeat:monthly' => "Monthly",
	'events_ui:repeat:yearly' => "Yearly",
	'repeat_ui:repeat_ends' => "Ends",
	'events_ui:repeat_ends:never' => "Never",
	'events_ui:repeat_ends:after' => "After %s occurrences",
	'events_ui:repeat_ends:on' => "On %s",
	
	'repeat_ui:repeat:weekly:weekday' => ' on %s',
	'repeat_ui:repeat_monthly_by' => 'Repeat by',
	'repeat_ui:repeat_monthly_by:day_of_month' => 'Day of the month',
	'repeat_ui:repeat_monthly_by:day_of_month:date' => ' on the %s day of the month',
	'repeat_ui:repeat_monthly_by:day_of_month:weekday' => ' on the %s %s of the month',
	'repeat_ui:repeat_monthly_by:day_of_week' => 'Day of the week',
	'repeat_ui:repeat_weekly_days' => 'Repeat on',
	
	/**
	 * Timezone names
	 */
	'timezone:name:US:AST' => 'Atlantic Standard Time',
	'timezone:name:US:EST' => 'Eastern Standard Time',
	'timezone:name:US:EDT' => 'Eastern Daylight Time',
	'timezone:name:US:CST' => 'Central Standard Time',
	'timezone:name:US:CDT' => 'Central Daylight Time',
	'timezone:name:US:MST' => 'Mountain Standard Time',
	'timezone:name:US:MDT' => 'Mountain Daylight Time',
	'timezone:name:US:PST' => 'Pacific Standard Time',
	'timezone:name:US:PDT' => 'Pacific Daylight Time',
	'timezone:name:US:AKST' => 'Alaska Time',
	'timezone:name:US:AKDT' => 'Alaska Daylight Time',
	'timezone:name:US:HST' => 'Hawaii Standard Time',
	'timezone:name:US:HAST' => 'Hawaii-Aleutian Standard Time',
	'timezone:name:US:HADT' => 'Hawaii-Aleutian Daylight Time',
	'timezone:name:US:SST' => 'Samoa Standard Time',
	'timezone:name:US:SDT' => 'Samoa Daylight Time',
	'timezone:name:US:CHST' => 'Chamorro Standard Time',

	/**
	 * Country list
	 */
	'timezone:country:??' => 'UTC',
	'timezone:country:AD' => 'Andorra',
	'timezone:country:AE' => 'United Arab Emirates',
	'timezone:country:AF' => 'Afghanistan',
	'timezone:country:AG' => 'Antigua and Barbuda',
	'timezone:country:AI' => 'Anguilla',
	'timezone:country:AL' => 'Albania',
	'timezone:country:AM' => 'Armenia',
	'timezone:country:AO' => 'Angola',
	'timezone:country:AR' => 'Argentina',
	'timezone:country:AS' => 'American Samoa',
	'timezone:country:AT' => 'Austria',
	'timezone:country:AU' => 'Australia',
	'timezone:country:AW' => 'Aruba',
	'timezone:country:AX' => 'Aland Islands',
	'timezone:country:AZ' => 'Azerbaijan',
	'timezone:country:BA' => 'Bosnia and Herzegovina',
	'timezone:country:BB' => 'Barbados',
	'timezone:country:BD' => 'Bangladesh',
	'timezone:country:BE' => 'Belgium',
	'timezone:country:BF' => 'Burkina Faso',
	'timezone:country:BG' => 'Bulgaria',
	'timezone:country:BH' => 'Bahrain',
	'timezone:country:BI' => 'Burundi',
	'timezone:country:BJ' => 'Benin',
	'timezone:country:BL' => 'Saint BarthÃ©lemy',
	'timezone:country:BM' => 'Bermuda',
	'timezone:country:BN' => 'Brunei',
	'timezone:country:BO' => 'Bolivia',
	'timezone:country:BR' => 'Brazil',
	'timezone:country:BS' => 'Bahamas',
	'timezone:country:BT' => 'Bhutan',
	'timezone:country:BV' => 'Bouvet Island',
	'timezone:country:BW' => 'Botswana',
	'timezone:country:BY' => 'Belarus',
	'timezone:country:BZ' => 'Belize',
	'timezone:country:CA' => 'Canada',
	'timezone:country:CC' => 'Cocos Islands',
	'timezone:country:CD' => 'Democratic Republic of the Congo',
	'timezone:country:CF' => 'Central African Republic',
	'timezone:country:CG' => 'Republic of the Congo',
	'timezone:country:CH' => 'Switzerland',
	'timezone:country:CI' => 'Ivory Coast',
	'timezone:country:CK' => 'Cook Islands',
	'timezone:country:CL' => 'Chile',
	'timezone:country:CM' => 'Cameroon',
	'timezone:country:CN' => 'China',
	'timezone:country:CO' => 'Colombia',
	'timezone:country:CR' => 'Costa Rica',
	'timezone:country:CU' => 'Cuba',
	'timezone:country:CV' => 'Cape Verde',
	'timezone:country:CX' => 'Christmas Island',
	'timezone:country:CY' => 'Cyprus',
	'timezone:country:CZ' => 'Czech Republic',
	'timezone:country:DE' => 'Germany',
	'timezone:country:DJ' => 'Djibouti',
	'timezone:country:DK' => 'Denmark',
	'timezone:country:DM' => 'Dominica',
	'timezone:country:DO' => 'Dominican Republic',
	'timezone:country:DZ' => 'Algeria',
	'timezone:country:EC' => 'Ecuador',
	'timezone:country:EE' => 'Estonia',
	'timezone:country:EG' => 'Egypt',
	'timezone:country:EH' => 'Western Sahara',
	'timezone:country:ER' => 'Eritrea',
	'timezone:country:ES' => 'Spain',
	'timezone:country:ET' => 'Ethiopia',
	'timezone:country:FI' => 'Finland',
	'timezone:country:FJ' => 'Fiji',
	'timezone:country:FK' => 'Falkland Islands',
	'timezone:country:FM' => 'Micronesia',
	'timezone:country:FO' => 'Faroe Islands',
	'timezone:country:FR' => 'France',
	'timezone:country:GA' => 'Gabon',
	'timezone:country:GB' => 'United Kingdom',
	'timezone:country:GD' => 'Grenada',
	'timezone:country:GE' => 'Georgia',
	'timezone:country:GF' => 'French Guiana',
	'timezone:country:GG' => 'Guernsey',
	'timezone:country:GH' => 'Ghana',
	'timezone:country:GI' => 'Gibraltar',
	'timezone:country:GL' => 'Greenland',
	'timezone:country:GM' => 'Gambia',
	'timezone:country:GN' => 'Guinea',
	'timezone:country:GP' => 'Guadeloupe',
	'timezone:country:GQ' => 'Equatorial Guinea',
	'timezone:country:GR' => 'Greece',
	'timezone:country:GS' => 'South Georgia and the South Sandwich Islands',
	'timezone:country:GT' => 'Guatemala',
	'timezone:country:GU' => 'Guam',
	'timezone:country:GW' => 'Guinea-Bissau',
	'timezone:country:GY' => 'Guyana',
	'timezone:country:HK' => 'Hong Kong',
	'timezone:country:HM' => 'Heard Island and McDonald Islands',
	'timezone:country:HN' => 'Honduras',
	'timezone:country:HR' => 'Croatia',
	'timezone:country:HT' => 'Haiti',
	'timezone:country:HU' => 'Hungary',
	'timezone:country:ID' => 'Indonesia',
	'timezone:country:IE' => 'Ireland',
	'timezone:country:IL' => 'Israel',
	'timezone:country:IM' => 'Isle of Man',
	'timezone:country:IN' => 'India',
	'timezone:country:IO' => 'British Indian Ocean Territory',
	'timezone:country:IQ' => 'Iraq',
	'timezone:country:IR' => 'Iran',
	'timezone:country:IS' => 'Iceland',
	'timezone:country:IT' => 'Italy',
	'timezone:country:JE' => 'Jersey',
	'timezone:country:JM' => 'Jamaica',
	'timezone:country:JO' => 'Jordan',
	'timezone:country:JP' => 'Japan',
	'timezone:country:KE' => 'Kenya',
	'timezone:country:KG' => 'Kyrgyzstan',
	'timezone:country:KH' => 'Cambodia',
	'timezone:country:KI' => 'Kiribati',
	'timezone:country:KM' => 'Comoros',
	'timezone:country:KN' => 'Saint Kitts and Nevis',
	'timezone:country:KP' => 'North Korea',
	'timezone:country:KR' => 'South Korea',
	'timezone:country:KW' => 'Kuwait',
	'timezone:country:KY' => 'Cayman Islands',
	'timezone:country:KZ' => 'Kazakhstan',
	'timezone:country:LA' => 'Laos',
	'timezone:country:LB' => 'Lebanon',
	'timezone:country:LC' => 'Saint Lucia',
	'timezone:country:LI' => 'Liechtenstein',
	'timezone:country:LK' => 'Sri Lanka',
	'timezone:country:LR' => 'Liberia',
	'timezone:country:LS' => 'Lesotho',
	'timezone:country:LT' => 'Lithuania',
	'timezone:country:LU' => 'Luxembourg',
	'timezone:country:LV' => 'Latvia',
	'timezone:country:LY' => 'Libya',
	'timezone:country:MA' => 'Morocco',
	'timezone:country:MC' => 'Monaco',
	'timezone:country:MD' => 'Moldova',
	'timezone:country:ME' => 'Montenegro',
	'timezone:country:MF' => 'Saint Martin',
	'timezone:country:MG' => 'Madagascar',
	'timezone:country:MH' => 'Marshall Islands',
	'timezone:country:MK' => 'Macedonia',
	'timezone:country:ML' => 'Mali',
	'timezone:country:MM' => 'Myanmar',
	'timezone:country:MN' => 'Mongolia',
	'timezone:country:MO' => 'Macao',
	'timezone:country:MP' => 'Northern Mariana Islands',
	'timezone:country:MQ' => 'Martinique',
	'timezone:country:MR' => 'Mauritania',
	'timezone:country:MS' => 'Montserrat',
	'timezone:country:MT' => 'Malta',
	'timezone:country:MU' => 'Mauritius',
	'timezone:country:MV' => 'Maldives',
	'timezone:country:MW' => 'Malawi',
	'timezone:country:MX' => 'Mexico',
	'timezone:country:MY' => 'Malaysia',
	'timezone:country:MZ' => 'Mozambique',
	'timezone:country:NA' => 'Namibia',
	'timezone:country:NC' => 'New Caledonia',
	'timezone:country:NE' => 'Niger',
	'timezone:country:NF' => 'Norfolk Island',
	'timezone:country:NG' => 'Nigeria',
	'timezone:country:NI' => 'Nicaragua',
	'timezone:country:NL' => 'Netherlands',
	'timezone:country:NO' => 'Norway',
	'timezone:country:NP' => 'Nepal',
	'timezone:country:NR' => 'Nauru',
	'timezone:country:NU' => 'Niue',
	'timezone:country:NZ' => 'New Zealand',
	'timezone:country:OM' => 'Oman',
	'timezone:country:PA' => 'Panama',
	'timezone:country:PE' => 'Peru',
	'timezone:country:PF' => 'French Polynesia',
	'timezone:country:PG' => 'Papua New Guinea',
	'timezone:country:PH' => 'Philippines',
	'timezone:country:PK' => 'Pakistan',
	'timezone:country:PL' => 'Poland',
	'timezone:country:PM' => 'Saint Pierre and Miquelon',
	'timezone:country:PN' => 'Pitcairn',
	'timezone:country:PR' => 'Puerto Rico',
	'timezone:country:PS' => 'Palestinian Territory',
	'timezone:country:PT' => 'Portugal',
	'timezone:country:PW' => 'Palau',
	'timezone:country:PY' => 'Paraguay',
	'timezone:country:QA' => 'Qatar',
	'timezone:country:RE' => 'Reunion',
	'timezone:country:RO' => 'Romania',
	'timezone:country:RS' => 'Serbia',
	'timezone:country:RU' => 'Russia',
	'timezone:country:RW' => 'Rwanda',
	'timezone:country:SA' => 'Saudi Arabia',
	'timezone:country:SB' => 'Solomon Islands',
	'timezone:country:SC' => 'Seychelles',
	'timezone:country:SD' => 'Sudan',
	'timezone:country:SE' => 'Sweden',
	'timezone:country:SG' => 'Singapore',
	'timezone:country:SH' => 'Saint Helena',
	'timezone:country:SI' => 'Slovenia',
	'timezone:country:SJ' => 'Svalbard and Jan Mayen',
	'timezone:country:SK' => 'Slovakia',
	'timezone:country:SL' => 'Sierra Leone',
	'timezone:country:SM' => 'San Marino',
	'timezone:country:SN' => 'Senegal',
	'timezone:country:SO' => 'Somalia',
	'timezone:country:SR' => 'Suriname',
	'timezone:country:ST' => 'Sao Tome and Principe',
	'timezone:country:SV' => 'El Salvador',
	'timezone:country:SY' => 'Syria',
	'timezone:country:SZ' => 'Swaziland',
	'timezone:country:TC' => 'Turks and Caicos Islands',
	'timezone:country:TD' => 'Chad',
	'timezone:country:TF' => 'French Southern Territories',
	'timezone:country:TG' => 'Togo',
	'timezone:country:TH' => 'Thailand',
	'timezone:country:TJ' => 'Tajikistan',
	'timezone:country:TK' => 'Tokelau',
	'timezone:country:TL' => 'East Timor',
	'timezone:country:TM' => 'Turkmenistan',
	'timezone:country:TN' => 'Tunisia',
	'timezone:country:TO' => 'Tonga',
	'timezone:country:TR' => 'Turkey',
	'timezone:country:TT' => 'Trinidad and Tobago',
	'timezone:country:TV' => 'Tuvalu',
	'timezone:country:TW' => 'Taiwan',
	'timezone:country:TZ' => 'Tanzania',
	'timezone:country:UA' => 'Ukraine',
	'timezone:country:UG' => 'Uganda',
	'timezone:country:UM' => 'United States Minor Outlying Islands',
	'timezone:country:US' => 'United States',
	'timezone:country:UY' => 'Uruguay',
	'timezone:country:UZ' => 'Uzbekistan',
	'timezone:country:VA' => 'Vatican',
	'timezone:country:VC' => 'Saint Vincent and the Grenadines',
	'timezone:country:VE' => 'Venezuela',
	'timezone:country:VG' => 'British Virgin Islands',
	'timezone:country:VI' => 'U.S. Virgin Islands',
	'timezone:country:VN' => 'Vietnam',
	'timezone:country:VU' => 'Vanuatu',
	'timezone:country:WF' => 'Wallis and Futuna',
	'timezone:country:WS' => 'Samoa',
	'timezone:country:YE' => 'Yemen',
	'timezone:country:YT' => 'Mayotte',
	'timezone:country:ZA' => 'South Africa',
	'timezone:country:ZM' => 'Zambia',
	'timezone:country:ZW' => 'Zimbabwe',
);

add_translation("en",
$english);