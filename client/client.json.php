<?php
// This client for ws_completion is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

header('Content-Type: text/plain');
require_once('./curl.php');
require_once('./demolib.php');
$curl = new curl;

/*
 * set up data
 */
$moodle						= 'http://moodle26'; // the site hosting the service, no trailing slash
$username					= 'student'; // who you want to log on as (don't use an admin, they work differently)
$servicename				= 'local_wscompletion'; // the web service name
$functionname				= 'wscompletion_status'; // the web service function to call
$wp2moodle_shared_secret	= 'abc123'; // known wp2moodle key for your installation

/*
 * part a - generate a token for the webservice
 * can work over non-SSL relatively securely
 * we need to create a token to represent the user/function they will use
 * this works with the wp2moodle plugin since it knows the encryption key we use
 */

$token_call_ecrypted = encrypt_string(http_build_query(array(
	"username" => $username,
	"servicename" => $servicename,
	"z" => rand(1,1500) // to ensure unique encryption string is generated
)), $wp2moodle_shared_secret);
$user_token = $curl->get($moodle . '/auth/wp2moodle/token.php?data=' . $token_call_ecrypted);

/*
 * part b - using the token to grab data
 *
 * the token represents both the user AND the service to use
 * we still pass in the function name to call within that service
 * also add the wsrestformat of JSON otherwise you get xml ...
 */
 
$raw = $curl->post($moodle . '/webservice/rest/server.php?wstoken='.json_decode($user_token)->token.'&wsfunction='.$functionname.'&moodlewsrestformat=json',array());
echo $raw;
