<?php

//Import the Database Functions
include("../../../../connect.php");

//Get all the HTTP Headers from the Request
$HTTPHeaders[] = "";

foreach (getallheaders() as $name => $value) 
{
	$HTTPHeaders[$name] = $value;
}

//Check if the request type coming in is POST or not

$AuthHeader = $HTTPHeaders["Authentication"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if ($AuthHeader != NULL)
	{
		//Uncomment following sections if Authentication header is to be used
		//$sql = "Select VendorId from `lamdrew_authorization`.`AuthorizedUsers` where VendorId='$AuthHeader'";
		//$result = runSQL($sql);
		
		//Check to see if a result returns
		//$ResultRows = $result->num_rows;
		$ResultRows = 1;
		if($ResultRows == 1)
		{
			if ($HTTPHeaders["Content-Type"] != "application/json")
			{
				header('HTTP/1.1 400 Bad Request');
				echo 'Missing Required Header';		
			}
			else
			{
				if($HTTPHeaders["Accept"] != "application/json")
				{
					header('HTTP/1.1 400 Bad Request');
					echo 'Missing Required Header';		
					
				}
				//Application logic begins in this else bracket
				else
				{
					$json = file_get_contents('php://input');
					$obj = json_decode($json);
					$mdn = $obj->MDN;
					$deviceId = $obj->deviceId;
					$pushId = $obj->pushId;
					$emailAddress = $obj->emailAddress;
					$Manufacturer = $obj->Manufacturer;
					$Model = $obj->Model;
					$OSVersion = $obj->OSVersion;
					$Product = $obj->Product;
					
					
					$UpdateSQL = "UPDATE `surveyapp`.`Users` SET pushID='$pushId' where MDN='$mdn' AND deviceId='$deviceId'";
												
					$UpdateResult = runSQL($UpdateSQL);
					
					if($UpdateResult == 1)
					{
					   header('Content-Type: application/json');
					   $arr = array('StatusCode' => 1);
					   echo json_encode($arr);
					}
					else
					{
					   header('Content-Type: application/json');
					   $arr = array('StatusCode' => $InsertResult);
					   echo json_encode($arr);
					}				
				}
			}
			//$result->free();
		}
		else
		{
			header('HTTP/1.1 403 Forbidden');
			echo 'You are forbidden user!';
		}
	}
	else
	{
		header('HTTP/1.1 403 Forbidden');
		echo "You are forbidden! ";
	}

}
else
{
	header('HTTP/1.1 405 Method Not Allowed');
}


?>
