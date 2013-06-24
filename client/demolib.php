<?php
function encrypt_string($value, $key) { 
	if (!$value) {return "";}
	$text = $value;
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key.$key), $text, MCRYPT_MODE_ECB, $iv);

	// encode data so that $_GET won't urldecode it and mess up some characters
	$data = base64_encode($crypttext);
    $data = str_replace(array('+','/','='),array('-','_',''),$data);
    return trim($data);
}

function html_show_array($array){
  echo "<table cellspacing=\"2\" cellpadding=\"2\" border=\"2\">\n";
  echo "<thead><tr><th>Course</th><th>CourseId</th><th>Started</th><th>Completed</th><th>Certificate</th></thead>";
  echo "<tbody>";

	$count = count($array);
	for ($i = 0; $i < $count; $i++) {
		$row = $array[$i];
		$started = $row['Started'];
		$completed = $row['Completed'];
		if ($started > 0) { $started = date("D, d M Y H:i:s", $row['Started']); }
		if ($completed > 0) { $completed = date("D, d M Y H:i:s", $row['Completed']); }
		echo "<tr>";
		echo "<td>".$row['CourseName']."</td>";
		echo "<td>".$row['CourseId']."</td>";
		echo "<td>".$started."</td>";
		echo "<td>".$completed."</td>";
		echo "<td>".$row['CertificateID']."</td>";
		echo "</tr>";

	}

  echo "</tbody>";
  echo "</table>\n";
}
