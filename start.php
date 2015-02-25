<?php

namespace Events\API;

const UPGRADE_VERSION = 20141215;

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/lib/events.php';
require_once __DIR__ . '/lib/hooks.php';
require_once __DIR__ . '/lib/functions.php';

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');
elgg_register_event_handler('upgrade', 'system', __NAMESPACE__ . '\\upgrade');

/**
 * Initialize plugin
 * @return void
 */
function init() {

	elgg_register_plugin_hook_handler('container_permissions_check', 'object', __NAMESPACE__ . '\\calendar_permissions');

	elgg_register_event_handler('delete', 'object', __NAMESPACE__ . '\\delete_event_handler');

	elgg_register_action('calendar/edit', __DIR__ . '/actions/calendar/edit.php');
	elgg_register_action('calendar/delete', __DIR__ . '/actions/calendar/delete.php');
	elgg_register_action('calendar/add_event', __DIR__ . '/actions/calendar/add_event.php');
	elgg_register_action('events/edit', __DIR__ . '/actions/events/edit.php');
	elgg_register_action('events/move', __DIR__ . '/actions/events/move.php');
	elgg_register_action('events/resize', __DIR__ . '/actions/events/resize.php');
	elgg_register_action('events/delete', __DIR__ . '/actions/events/delete.php');
}

}

/**
 * Run upgrade scripts
 * @return void
 */
function upgrade() {
	if (elgg_is_admin_logged_in()) {
		include_once __DIR__ . '/lib/upgrades.php';
	}
}
