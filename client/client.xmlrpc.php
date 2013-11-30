<?php
// This client for ws_completion is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

header('Content-Type: text/plain');
require_once('./curl.php');
require_once('./demolib.php'); // help library for this demo
$curl = new curl;

/*
 * set up data
 */
$moodle						= 'http://moodle26'; // the site hosting the service, no trailing slash
$username					= 'student'; // who you want to log on as (don't use an admin, they work differently)
$servicename				= 'local_wscompletion'; // the web service name
$functionname				= 'wscompletion_status'; // the web service function to call
$wp2moodle_shared_secret	= 'abc123'; // known wp2moodle key for your installation


doEcho ("Part A","----------------------------------------");
/*
 * part a - generate a token for the webservice
 * can work over non-SSL relatively securely
 * we need to create a token to represent the user/function they will use
 * this works with the wp2moodle plugin since it knows the encryption key we use
 */

// 1.1. build the encrypted string we will use to generate the token
$qs = http_build_query(array(
	"username" => $username,
	"servicename" => $servicename,
	"z" => rand(1,1500)
));
$getdata = encrypt_string($qs, $wp2moodle_shared_secret);

// 1.2. get wp2moodle to generate a token for this user / service
$curl_return = $curl->get($moodle . '/auth/wp2moodle/token.php?data=' . $getdata);

// 1.3. the response is a json packet in {token:value} format; decode it
$login_token = json_decode($curl_return)->token; // seems to convert single key to its value; no need to look at *->token
doEcho("Token", $login_token);



doEcho ("Part B","----------------------------------------");
/*
 * part b - using the token to grab data
 *
 * the token represents both the user AND the service to use
 * we still pass in the function name to call within that service
 */
 
// 2.1. post the token to the webservice

$post = xmlrpc_encode_request($functionname, array()); // empty array = no parameters

doEcho ("Service", $moodle . '/webservice/xmlrpc/server.php'. '?wstoken=' . $login_token);
$raw = $curl->post($moodle . '/webservice/xmlrpc/server.php'. '?wstoken=' . $login_token, $post);
die(); // debug

// 2.2 decode the response
$resp = xmlrpc_decode($raw);

//2.3 here is where we would process $resp and pull out data from it
doEcho("Response", $resp);
