<?php 

	register();

	function register(){
		$json_response=array();
		$json_response['status']="ERROR";

		$email_id=fetch($_POST,"email_id");
		$name=fetch($_POST,"name");
		$mobile=fetch($_POST,"mobile");
		$password=fetch($_POST,"password");

		// Validate ALL parameters
		if (!validate("Email ID",$email_id,5,100,"EMAIL") ||
			!validate("Name",$name,5,100,"VARCHAR") ||
			!validate("mobile",$mobile,10,10,"VARCHAR") ||
			!validate("Password",$password,5,100,"PASSWORD") ) {
			$json_response['message']=$ERR_MSG;
			echo json_encode($json_response);
			return;
		}

		$resp=execute(
						"INSERT INTO tUser ( 'email_id','name','mobile','password' ) VALUES ( '$email_id','$name','$mobile','$password' )"
					);
		if ( $resp['STATUS'] == "ERROR" ) {
			$json_response["message"]="There was an error during registration, please try again later.";
			echo json_encode($json_response);
			return;
		}

		$json_response["status"]="OK";
		$json_response["message"]="Your account was successfully created, please click on login & provide your credentials to login.";
		echo json_encode($json_response);
		return;
	}

?>