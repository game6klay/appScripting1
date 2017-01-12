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
					$surveyDate = $obj->Date;
					
					$surveyDate = date("Y-m-d");
					
					$SelectSurvey = "Select * from `surveyapp`.`Survey` where `Date`='$surveyDate';";
					$SelectResult = runSQL($SelectSurvey);
					
					$emparray = array("StatusCode"=>1, "Rows"=>mysqli_num_rows($SelectResult));					
					while($row =mysqli_fetch_assoc($SelectResult))
					{
						$emparray[] = $row;
					}

					header('Content-Type: application/json');
					echo json_encode($emparray);
								
				}
			}
//			$result->free();
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
