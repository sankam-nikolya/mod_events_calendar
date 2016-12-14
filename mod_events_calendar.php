<?php
/**
 * @copyright	Copyright © 2016 - All rights reserved.
 * @license		GNU General Public License v2.0
 */
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

if(JFile::exists(JPATH_ROOT . '/components/com_events/events.php')) {
	$category = (int) $params->get('category');
	$type     = (int) $params->get('type', 2, 'INT');
	$controls = (bool) $params->get('controls', 0, 'INT');

	require JModuleHelper::getLayoutPath('mod_events_calendar', $params->get('layout', 'default'));
}
