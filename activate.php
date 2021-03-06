<?php

namespace Events\API;

require_once __DIR__ . '/vendor/autoload.php';

$plugin_id = basename(__DIR__);

$subtypes = array(
	Calendar::SUBTYPE => get_class(new Calendar),
	Event::SUBTYPE => get_class(new Event),
);

foreach ($subtypes as $subtype => $class) {
	if (!update_subtype('object', $subtype, $class)) {
		add_subtype('object', $subtype, $class);
	}
}

$upgrade_version = elgg_get_plugin_setting('upgrade_version', $plugin_id);
if (!$upgrade_version) {
	elgg_set_plugin_setting('upgrade_version', 20141215, $plugin_id);
}

daily_build_reminders(null, null, null, null);