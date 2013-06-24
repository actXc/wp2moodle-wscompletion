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
 * Post installation and migration code.
 *
 * This file replaces:
 *   - STATEMENTS section in db/install.xml
 *   - lib.php/modulename_install() post installation hook
 *   - partially defaults.php
 *
 * @package    local
 * @subpackage ws_completion
 * @copyright  2012 tim st.clair  {@link http://about.me/timstclair}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

function xmldb_local_wscompletion_uninstall () {
    global $CFG, $DB;
    $dbman = $DB->get_manager();

	// Pretty simple uninstall, just drop the views
	try {
		$sql = "DROP VIEW {vw_certificates}";
        $DB->execute($sql);
		$sql2 = "DROP VIEW {vw_completions}";
        $DB->execute($sql2);
	} catch (Exception $ignored) {
	}
	return true;	
}
