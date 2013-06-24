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

/**
 * Completion webservice - reports on the completion status of courses for the user (read only)
 *
 * @package   wscompletion
 * @copyright 2012 Tim St.Clair (http://frumbert.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->libdir . "/moodlelib.php");

class wscompletion_lib extends external_api {
	
	public static function completion_status_parameters() {
		return new external_function_parameters(
            array(
               //if I had any parameters, they would be described here. But I don't have any, so this array is empty.
            )
        );
		//return new external_function_parameters(
		//	array('foo' => new external_value(PARAM_TEXT, 'no parameters are required, but you have to send one anyway'))
		//);
	}
	public static function completion_status_returns() {

        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'CourseName' => new external_value(PARAM_TEXT, 'Full name of course'),
                    'CourseId' => new external_value(PARAM_TEXT, 'Short name of course'),
                    'Started' => new external_value(PARAM_INT, 'timestamp if/when the user started course'),
                    'Completed' => new external_value(PARAM_INT, 'timestamp if/when user has completed course'),
                    'WPUserId' => new external_value(PARAM_TEXT, 'idnumber (text) of this user'),
                    'WPUsername' => new external_value(PARAM_TEXT, 'moodle login name'),
                    'CertificateID' => new external_value(PARAM_INT, 'certificate number if a certificate was generated for this completion'), 
                    'CertificateCode' => new external_value(PARAM_TEXT, 'certificate code if a certificate was generated for this completion - required if you want to use pickup.php for certificate retrieval')
                )
            )
        );		
	}
	
	public static function completion_status($params) {
        global $CFG, $DB, $USER;

        // first thing to do is filter by ip address if required by settings
        $settings = get_config('local_wscompletion');
        if (empty($settings->netLock) === false)
        {
            if (address_in_subnet(getremoteaddr(), $settings->netLock) === false)
                return xmlrpc_encode(array('faultCode' => 1, 'faultString' => "Access denied"));    // this actually gets filtered to a 404, Not found error by the moodle xmlrpc webservice libraries
        }
        
        require_once($CFG->dirroot . "/course/lib.php");

        // Context validation
		// $params = self::validate_parameters(self::completion_status_parameters(), array('foo' => $foo));
   		$context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

		// this page is executed in the context of the user specified in the token, so we have a $USER
		// Just look up everything for this user/token (moodle_database.php: get_records returns as array)
        $rows = $DB->get_records('vw_completions', array('WPUsername' => $USER->username));
		
		// now convert the rows into the returns structure - can't just return $rows :(
		$ret = array();
		foreach ($rows as $row) {
			$ret[] = array(
                    'CourseName' => $row->coursename,
                    'CourseId' => $row->courseid,
                    'Started' => intval($row->started),
                    'Completed' => intval($row->completed),
                    'WPUserId' => $row->wpuserid,
                    'WPUsername' => $row->wpusername,
                    'CertificateID' => intval($row->certificateid), 
                    'CertificateCode' => $row->certificatecode
            );
		}
		return $ret;
	}

}
