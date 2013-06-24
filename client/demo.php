<?php

require_once('./curl.php'); // curl wrapper library
require_once('./demolib.php'); // help library for this demo
$username = $_POST["uname"];
?>

<html>
	<head>
		<title>Web Service Demo</title>
		<link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
		<style>* {font-family:'Muli';}</style>
	</head>
	<body>
		<h1>Enter a student login:</h1>
		<form action="demo.php" method="post">
		Username: <input type="text" name="uname" value="<?php echo $username ?>" />
		<input type="submit" />
		</form>

		<h2>Completion data for this user:</h2>

<?php

if ($username != '') {
	$curl = new curl;
	$moodle						= 'http://your-moodle-site/moodle'; // the site hosting the service, no trailing slash
	$servicename				= 'local_wscompletion'; // the web service name
	$functionname				= 'wscompletion_status'; // the web service function to call
	$wp2moodle_shared_secret	= '893769c7-591e-45a5-90b9-12e725e0a219'; // known wp2moodle shared secret key
	$qs = http_build_query(array(
		"username" => $username,
		"servicename" => $servicename,
		"z" => rand(1,1500)
	));
	$getdata = encrypt_string($qs, $wp2moodle_shared_secret);
	$curl_return = $curl->get($moodle . '/auth/wp2moodle/token.php?data=' . $getdata);
	$login_token = json_decode($curl_return)->token; // seems to convert single key to its value; no need to look at *->token
	$post = xmlrpc_encode_request($functionname, array()); // empty array = no parameters
	$raw = $curl->post($moodle . '/webservice/xmlrpc/server.php'. '?wstoken=' . $login_token, $post);
	$resp = xmlrpc_decode($raw);
	html_show_array($resp);
}
?>

	</body>
</html>
