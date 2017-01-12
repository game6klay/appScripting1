<?php

//define("pushURL", "https://fcm.googleapis.com/fcm/send");
define("pushURL", "https://carvilus.com");
define("AuthorizationKey", "AIzaSyBo_Z4kuKoKWa6nl8i24hrXH6rM0jCMFK8");

$data = array("OperationId" => "MEgetAspToken", "LCId" => "54009200000129994597;54,000,Apple,iPad6.7,WiFi", "AppId" => "V4B", "DevId" => "12345678-1234-1234-1234-123456789012", "MDN"=>"9082857753", "DevType"=>"IOS", "DevName"=>"Test", "Type"=>"VMA", "SpcMEToken"=>"AQm/BlwCTYOAdue+9ddvhBOekP78KtvwVt7uo3YHbTpQlw==");

$data_string = json_encode($data);

$ch = curl_init(pushURL);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Accept: application/json',
    'Content-Length: ' . strlen($data_string),
	'Authorization: key='.AuthorizationKey)
);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

echo "Sent 2 request";


?>