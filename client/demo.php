<?php

require_once('./curl.php'); // curl wrapper library
require_once('./demolib.php'); // help library for this demo
$username = $_POST["uname"];
$ssecret = $_POST["ssecret"];
?>

<html>
	<head>
		<title>Web Service Demo</title>
		<link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
		<style>* {font-family:'Muli';}label{display:block}</style>
	</head>
	<body>
		<h1>Enter a student login:</h1>
		<form action="demo.php" method="post">
			<label>Username: <input type="text" name="uname" value="<?php echo $username ?>" /></label>
			<label>Shared secret: <input type="text" name="ssecret" value="<?php echo $ssecret ?>" /></label>
			<label><input type="submit" /></label>
		</form>
		<h2>Completion data for this user:</h2>

<?php

if ($username != '') {
	$curl = new curl;
	$moodle						= 'http://'.$_SERVER["SERVER_NAME"]; // the site hosting the service, no trailing slash
	$servicename				= 'local_wscompletion'; // the web service name
	$functionname				= 'wscompletion_status'; // the web service function to call
	$qs = http_build_query(array(
		"username" => $username,
		"servicename" => $servicename,
		"z" => rand(1,1500) // jumble encryption a bit
	));
	
	// you have to generate a token for this username AND servicename
	// we pass in the encrypted data and it returns our token as a JSON string - {token:value}
	$getdata = encrypt_string($qs, $ssecret);
	$curl_return = $curl->get($moodle . '/auth/wp2moodle/token.php?data=' . $getdata);
	$login_token = json_decode($curl_return)->token; // we only want the token value

	// example uses XMLRPC to encode a request
	$post = xmlrpc_encode_request($functionname, array()); // empty array = no parameters
	$raw = $curl->post($moodle . '/webservice/xmlrpc/server.php'. '?wstoken=' . $login_token, $post);
	
	// and how to decode the response
	$resp = xmlrpc_decode($raw);
	
	// and just jump it out using a helper to format it
	html_show_array($resp);
}
?>

	</body>
</html>
