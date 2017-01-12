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
					$deviceId = $obj->deviceId;
					$pushId = $obj->pushId;
					$emailAddress = $obj->emailAddress;
					$password = $obj->pass;
					$Manufacturer = $obj->Manufacturer;
					$Model = $obj->Model;
					$Name = $obj->Name;
					$OSVersion = $obj->OSVersion;
					$Product = $obj->Product;
					
					$PasswordHash = hash("sha256",$password);
					$timestamp = date('Y-m-d G:i:s');
					$hash = hash("sha256",$emailAddress.$timestamp);
					$CreateAccountSQL = "Insert into `surveyapp`.`Users` VALUES ('$timestamp', 'PENDING', '$hash', '$Name', '$emailAddress', '$PasswordHash');";
					
					$CreateAccountResult = runSQL($CreateAccountSQL);
					
					if($CreateAccountResult == 1)
					{
						
						
						$InsertSQL = "INSERT INTO  `surveyapp`.`UserDetails` (`CreationDate`,`LastUpdateDate`,`deviceId`,`emailAddress`,`pushId`,`Manufacturer`, `Model`,`OSVersion`,`Product`) VALUES ('$timestamp', null, '$deviceId',  '$emailAddress', '$pushId','$Manufacturer', '$Model', '$OSVersion', '$Product');";
						$InsertResult = runSQL($InsertSQL);
						  if($InsertResult == 1)
							{
							  header('Content-Type: application/json');
							  $arr = array('StatusCode' => 1);
							  echo json_encode($arr);
							}
							else if ($InsertResult = 1062)
							{
								$UpdateSQL = "Update `surveyapp`.`UserDetails` set LastUpdateDate='$timestamp', pushId='$pushId', Manufacturer='$Manufacturer', Model='$Model', OSVersion='$OSVersion', Product='$Product' where emailAddress='$emailAddress' AND deviceId= '$deviceId'";
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
									$arr = array('StatusCode' => 0);
									echo json_encode($arr);
								}
								
							}
							else
							{						
								//Unable to Insert into the Database
								header('Content-Type: application/json');								
								$arr = array('StatusCode' => 0);
								echo json_encode($arr);
							}
					}
					else if ($CreateAccountResult == 1062)
					{
						header('Content-Type: application/json');
						$arr = array('StatusCode' => 1062);
						echo json_encode($arr);
					}
					else
					{						
						//Unable to Insert into the Database
						header('Content-Type: application/json');								
						$arr = array('StatusCode' => 0);
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
