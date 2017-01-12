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
					$OSVersion = $obj->OSVersion;
					$Product = $obj->Product;
					
					$PasswordHash = hash("sha256",$password);
					
					$SelectSQL = "Select Name, AccountStatus from `surveyapp`.`Users` where emailAddress='$emailAddress' AND Password= '$PasswordHash'"; 
														
					$SelectResult = runSQL($SelectSQL);
					
					while($row = $SelectResult->fetch_assoc())
					{
						$AccountStatus = $row['AccountStatus'];
					}
					
					if($AccountStatus == "ACTIVE")
					{
						if($SelectResult == 1 & mysqli_num_rows($SelectResult))
						{
						  header('Content-Type: application/json');
						  $timestamp = date('Y-m-d G:i:s');
						$InsertSQL = "INSERT INTO  `surveyapp`.`UserDetails` (
						`CreationDate`,
						`LastUpdateDate`,
						`deviceId` ,
						`emailAddress`,
						`pushId` ,
						`Manufacturer` ,
						`Model` ,
						`OSVersion` ,
						`Product`					
						)
						VALUES (
						'$timestamp', null, '$deviceId',  '$emailAddress', '$pushId','$Manufacturer', '$Model', '$OSVersion', '$Product');";
						
						$InsertResult = runSQL($InsertSQL);
						
						  
						  if($InsertResult == 1)
							{
							  $arr = array('StatusCode' => 1);
							  echo json_encode($arr);
							}
							else if ($InsertResult = 1062)
							{
								$UpdateSQL = "Update `surveyapp`.`UserDetails` set LastUpdateDate='$timestamp', pushId='$pushId', Manufacturer='$Manufacturer', Model='$Model', OSVersion='$OSVersion', Product='$Product' where emailAddress='$emailAddress' AND deviceId= '$deviceId'";
								$UpdateResult = runSQL($UpdateSQL);
								if($UpdateResult == 1)
								{
									$arr = array('StatusCode' => 1);
									echo json_encode($arr);
								}
								else
								{
									$arr = array('StatusCode' => 0);
									echo json_encode($arr);
								}
								
							}
							else
							{						
								//Unable to Insert into the Database							
								$arr = array('StatusCode' => 0);
								echo json_encode($arr);
							}
						}
						else
						{
								//Unable to find user and password combination in the database
						   header('Content-Type: application/json');
						   $arr = array('StatusCode' => 15);
						   echo json_encode($arr);
						}
					}
					else if ($AccountStatus == "PENDING")
					{
						//Account is Currently in Pending Status, requires validation
						header('Content-Type: application/json');
						$arr = array('StatusCode' => 10);
						echo json_encode($arr);
					}
					else
					{
						//Account Has Been Disabled
						header('Content-Type: application/json');
						$arr = array('StatusCode' => 15);
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
