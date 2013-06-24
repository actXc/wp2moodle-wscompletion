<?php
// This file is part of Moodle - http://moodle.org/
//
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

/**
 * Web service template plugin related strings
 * @package   wscompletion
 * @copyright 2012 Tim St.Clair (http://timstclair.me)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Completion status';
$string['certificatesnotfound'] = 'The certificates db / plugin was not found; cannot install.';
$string['settingsTitle'] = 'WsCompletion Settings';
$string['netLockDisplayName'] = 'Consumer network lock';
$string['netLockDescription'] = 'If not blank provides a method to enable the webservice for only selected consumer IP addresses. ' . 
                                'For formatting details see the comments for the address_in_subnet() function in moodlelib.php.';