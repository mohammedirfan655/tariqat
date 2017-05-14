<?php 

	register();

	function register(){

		global $ERR_MSG;
		$json_response=array();
		$json_response['status']="ERROR";

		$email_id=fetch($_POST,"email_id");
		$name=fetch($_POST,"name");
		$mobile=fetch($_POST,"mobile");
		$password=fetch($_POST,"password");

		// Validate ALL parameters
		if (!validate("Email ID",$email_id,5,100,"EMAIL") ||
			!validate("Name",$name,5,100,"varchar") ||
			!validate("mobile",$mobile,10,10,"varchar") ||
			!validate("Password",$password,5,100,"PASSWORD") ) {
			$json_response['message']=$ERR_MSG;
			echo json_encode($json_response);
			return;
		}

		$resp=execute(
						"INSERT INTO tUser ( `email_id`,`name`,`mobile`,`password` ) VALUES ( '$email_id','$name','$mobile','$password' )"
					,true);
		if ( $resp['STATUS'] == "ERROR" ) {
			switch($resp['SQL_ERROR_CODE']) {
				case 1062 :
					$json_response["message"]="This Email ID has already been used, please try with different credentials";
					break;
				default :
					$json_response["message"]="There was an error during registration, please try again later.";
					break;
			}
			echo json_encode($json_response);
			return;
		}

		$json_response["status"]="OK";
		$json_response["message"]="Your account was successfully created, please click on login & provide your credentials.";
		echo json_encode($json_response);
		return;
	}

?>