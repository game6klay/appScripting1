<?php
	$Password = $_POST["Password"];
	$MDN = $_POST["MDN"];
	
	$LCId = "51000100000112345678;51,4.4.2,Browser,WebRTC,WiFi";
	$AppId = "WALVME";
	$DevName = "Web Client";
	$OperationId = "MElogin";
	$ClientIP = $_SERVER['REMOTE_ADDR'];
	
	$Username="9082857753";
	
	$url = "https://198.226.25.62:9291/ium-ME/";
	
	$fields = array(
		'OperationId' => $OperationId,
		'LCId' => $LCId,
		'AppId' => $AppId,
		'DevId' => "96ebe105-819b-ee70-e1e8-4e07180e50e4",
		'DevName' => $DevName,
		'MDN' => $MDN,
		'AMSSOLogin' => $MDN,
		//'AMSSOPwd' => '4699782829',
		//'AMSSOPwd' => '12345678'
		'AMSSOPwd' => $Password,
		//'AMSSOPwd' => 'strumsoft123'
		//'AMSSOPwd' => 'view$0nic',
		//'MEPin' => '96C241CD',
		'PRIDNotification' => 'No'
	);
	
	$jsonrequestobject = json_encode($fields);
	
	// Open connection
	$ch = curl_init();

	//Set Headers
	    $headers = array(
             'Content-Type: application/json',
			 'Content-Encoding: gzip',
			 'Accept: application/json',
			 'Accept-Encoding: gzip'
        );
	
	// Set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_ENCODING , "gzip");

	// Disabling SSL Certificate support temporarly
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, gzencode($jsonrequestobject));

	// Execute post
	$result = curl_exec($ch);
	if ($result === FALSE) {
		die('Curl failed: ' . curl_error($ch));
	}

	// Close connection
	curl_close($ch);
	echo $result."<br>";
	
	$jsonresultobject = json_decode($result);
	$StatusCode = $jsonresultobject->{'StatusCode'};
	
?>
