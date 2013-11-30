<?php

// This file keeps track of upgrades to
// the certificate module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installation to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

defined('MOODLE_INTERNAL') || die;

function xmldb_local_wscompletion_upgrade($oldversion=0) {

    global $CFG, $DB;
    $dbman = $DB->get_manager();


    if ($oldversion < 2013050703) {
			
		if ($dbman->table_exists('certificate')) {
			// an inner joined view used by the completions view to avoid joining on a subquery
			$sql = "CREATE OR REPLACE VIEW {vw_certificates} AS (
						SELECT T1.course AS CourseID, T2.id AS CertificateID, T2.userid AS UserID, T2.code AS CertificateCode
						FROM {certificate} T1
						JOIN {certificate_issues} T2 ON T1.id = T2.certificateid
					)";
	        $DB->execute($sql);
	    	
			// create a view for course completion and user details with a left outer join to link in rows where a certificate is defined
			unset($sql);
			$sql = "CREATE OR REPLACE VIEW {vw_completions} AS (
						SELECT C.fullname as CourseName, C.idnumber AS CourseId, R.timestarted AS Started, 
								R.timecompleted AS Completed, U.idnumber AS WPUserId, U.username AS WPUsername, 
								D.CertificateID AS CertificateID, D.CertificateCode AS CertificateCode
						FROM {course_completions} R
						JOIN {course} C ON R.course = C.id
						JOIN {user} U ON R.userid = U.id
						LEFT JOIN {vw_certificates} D ON (C.id = D.CourseID AND U.id = D.UserID)
					)";
	        $DB->execute($sql);
	
        } else {
        	// certificate is not available, so just create nulls on those columns
			$sql = "CREATE OR REPLACE VIEW {vw_completions} AS (
						SELECT C.fullname as CourseName, C.idnumber AS CourseId, R.timestarted AS Started, 
								R.timecompleted AS Completed, U.idnumber AS WPUserId, U.username AS WPUsername, 
								null AS CertificateID, null AS CertificateCode
						FROM {course_completions} R
						JOIN {course} C ON R.course = C.id
						JOIN {user} U ON R.userid = U.id
					)";
	        $DB->execute($sql);
        }

	    // savepoint reached
    	upgrade_plugin_savepoint(true, 2013050703, 'wscompletion', 'db views');

   		// tell the service table about its shortname so /login/token.php can work!
		$DB->set_field('external_services', 'shortname', 'wscompletion', array('component'=>'local_wscompletion'));
     	upgrade_plugin_savepoint(true, 2013050703, 'wscompletion', 'shortname');
        
	}	
	return true;
}