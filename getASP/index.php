<?php

# An HTTP POST request example

# a pass-thru script to call my Play server-side code.
# currently needed in my dev environment because Apache and Play run on
# different ports. (i need to do something like a reverse-proxy from
# Apache to Play.)

# data needs to be POSTed to the Play url as JSON.
# (some code from http://www.lornajane.net/posts/2011/posting-json-data-with-php-curl)
$data = array("OperationId" => "MEgetAspToken", "LCId" => "54009200000129994597;54,000,Apple,iPad6.7,WiFi", "AppId" => "V4B", "DevId" => "12345678-1234-1234-1234-123456789012", "MDN"=>"9082857753", "DevType"=>"IOS", "DevName"=>"Test", "Type"=>"VMA", "SpcMEToken"=>"AQm/BlwCTYOAdue+9ddvhBOekP78KtvwVt7uo3YHbTpQlw==");

$data_string = json_encode($data);

$ch = curl_init('https://69.78.101.46:444/ium-ME/');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Accept: application/json',
    'Content-Length: ' . strlen($data_string))
);
curl_setopt($ch,CURLOPT_ENCODING , "gzip");
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

echo "Sent 1 request";

?>