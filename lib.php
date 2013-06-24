<?php

/**
 * Web service template plugin related strings
 * @package   wscompletion
 * @copyright 2012 Tim St.Clair (http://timstclair.me)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// cron task to create tokens for students
function wscompletion_cron () {
	$sql = "SELECT EXS.`id` AS ServiceId, EXF.`id` AS FunctionId
			FROM {external_services} EXS
			INNER JOIN {external_services_functions} EXF
			ON EXS.`id` = EXF.`externalserviceid`
			WHERE EXS.`shortname` = 'completionstatus'";

	// 
	$sql = "SELECT `id` FROM {external_services}
			WHERE `shortname` = 'completionstatus'";


	// users to insert
	$sql = "SELECT `id` FROM {users}
			WHERE id NOT IN (SELECT userid FROM {external_tokens})
			AND auth = 'wp2moodle'";
	
}
