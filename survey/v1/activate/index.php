<?php

	include("../../../../connect.php");

	$Hash=$_GET["a"];
	$emailAddress=$_GET["email"];
	
	$SelectSQL = "Select * from `surveyapp`.`Users` where emailAddress='$emailAddress' AND Hash= '$Hash'";
	
	$SelectResult = runSQL($SelectSQL);
	
	if($SelectResult == 1)
	{
		$UpdateSQL = "UPDATE `surveyapp`.`Users` SET `AccountStatus`='ACTIVE' where emailAddress='$emailAddress' AND Hash= '$Hash'";
		$UpdateResult = runSQL($UpdateSQL);
		if($UpdateResult == 1)
		{
			$UpdateSQL = "UPDATE `surveyapp`.`Users` SET `Hash`='' where emailAddress='$emailAddress' AND AccountStatus= 'Active'";
			$UpdateResult = runSQL($UpdateSQL);
			echo "<title> Account Activation </title> Successfully activated your account. Please return to the app and log in to begin.";
		}
	}
	else
	{
		echo "<title> Account Activation </title> Unable to activate your account at this time.";
	}
?>