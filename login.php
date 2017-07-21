<?php 

	login();

	function login(){

		global $ERR_MSG;
		$json_response=array();
		$json_response['status']="ERROR";

		$email_id=fetch($_POST,"email_id");
		$password=fetch($_POST,"password");

		// Validate ALL parameters
		if (!validate("Email ID",$email_id,5,100,"EMAIL") ||
			!validate("Password",$password,5,100,"PASSWORD") ) {
			$json_response['message']=$ERR_MSG;
			echo json_encode($json_response);
			return;
		}

		// Logout if you have not already loggedin
		logout();

		$resp=execute("SELECT * FROM tUser WHERE `email_id`='$email_id' AND `password`='$password'");
		if ( $resp[0]['STATUS'] == "ERROR" ) {
			$json_response["message"]="Error occured while logging in, please try again later.";
			echo json_encode($json_response);
			return;
		}
		if ( $resp[0]['NROWS'] == 0 ) {
			$json_response["message"]="Please provide valid credentials.";
			echo json_encode($json_response);
			return;
		}

		$_SESSION['logged_in']=1;
		$_SESSION['email_id']=$resp[0]["email_id"];
		$_SESSION['user_id']=$resp[0]["user_id"];
		$json_response["user_row"]=$resp[0];
		$json_response["status"]="OK";
		echo json_encode($json_response);
		return;
	}

	function logout() {

		$_SESSION="";
		$_SESSION['logged_in']=0;
		$_SESSION['email_id']="";
		$_SESSION['user_id']="";

	}
?>