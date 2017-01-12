<?php

	$json = file_get_contents('php://input');
	$obj = json_decode($json);
	
	print_r($obj);
	
	$myfile = fopen("input.log", "a") or die("unable to open");
	fwrite($myfile, "Incoming request");
	
?>