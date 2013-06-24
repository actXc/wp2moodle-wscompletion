<?php

// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// add page item to admin menu

require_once($CFG->libdir . '/moodlelib.php');
require_once($CFG->libdir . '/adminlib.php');

$settings = new admin_settingpage('local_wscompletion', get_string('settingsTitle', 'local_wscompletion'));

$ADMIN->add('localplugins', $settings);

$settings->add(new admin_setting_configtext('local_wscompletion/netLock', 
											get_string('netLockDisplayName', 'local_wscompletion'), 
											get_string('netLockDescription', 'local_wscompletion'), 
											'', 
											PARAM_TEXT));
