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

function xmldb_local_wscompletion_install () {
    global $CFG, $DB;
    $dbman = $DB->get_manager();

	if ($dbman->table_exists('certificate')) { // a) local plugins execute after others so we can install certificate at the same time, b) table_exists doesn't use {name} notation

		// an inner joined view used by the completions view to avoid joining on a subquery
		$sql = "CREATE OR REPLACE VIEW {vw_certificates} AS (
					SELECT T1.`course` AS CourseID, T2.`id` AS CertificateID, T2.`userid` AS UserID, T2.`code` AS CertificateCode
					FROM {certificate} T1
					JOIN {certificate_issues} T2 ON T1.`id` = T2.`certificateid`
				)";
        $DB->execute($sql);
    	
		// create a view for course completion and user details with a left outer join to link in rows where a certificate is defined
		unset($sql);
		$sql = "CREATE OR REPLACE VIEW {vw_completions} AS (
					SELECT C.fullname as CourseName, C.`idnumber` AS CourseId, R.`timestarted` AS Started, 
							R.`timecompleted` AS Completed, U.`idnumber` AS WPUserId, U.`username` AS WPUsername, 
							D.`CertificateID` AS CertificateID, D.`CertificateCode AS CertificateCode
					FROM {course_completions} R
					JOIN {course} C ON R.`course` = C.`id`
					JOIN {user} U ON R.`userid` = U.`id`
					LEFT JOIN {vw_certificates} D ON (C.`id` = D.`CourseID` AND U.`id` = D.`UserID`)
				)";
        $DB->execute($sql);

		// tell the service table about its shortname so /login/token.php can work!
		$DB->set_field('external_services', 'shortname', 'wscompletion', array('component'=>'local_wscompletion'));

		
	} else {
		// can't find certificate table, bail
		throw new moodle_exception('certificatesnotfound', 'webservice');
	}	
	return true;	
}
