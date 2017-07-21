<?php 

	change_password();

	function change_password(){

		global $ERR_MSG;
		$json_response=array();
		$json_response['status']="ERROR";

		$email_id=fetch($_POST,"email_id");
		$current_password=fetch($_POST,"current_password");
		$new_password=fetch($_POST,"new_password");
		$confirm_pwd=fetch($_POST,"confirm_pwd");

		// Validate ALL parameters
		if (!validate("Email ID",$email_id,5,100,"EMAIL") ||
			!validate("Current Password",$current_password,5,100,"PASSWORD") ||
			!validate("New Password",$new_password,5,100,"PASSWORD") ||
			!validate("Confirm Password",$confirm_pwd,5,100,"PASSWORD") ) {
			$json_response['message']=$ERR_MSG;
			echo json_encode($json_response);
			return;
		}

		if ( $new_password != $confirm_pwd ) {
			$json_response['message']="Passwords do not match";
			echo json_encode($json_response);
			return;
		}

		$resp=execute(
						"SELECT * FROM tUser WHERE `email_id`='$email_id' AND `password`='$current_password'"
					,false);
		if ( $resp[0]['STATUS'] == "ERROR" ) {
			$json_response["message"]="There was an error updating password, please try again later.";
			echo json_encode($json_response);
			return;
		}
		if ( $resp[0]['NROWS'] == 0 ) {
			$json_response["message"]="please enter valid current password.";
			echo json_encode($json_response);
			return;
		}

		$resp=execute(
						"UPDATE tUser SET `password`='$new_password' WHERE `email_id`='$email_id'"
					,true);
		if ( $resp['STATUS'] == "ERROR" ) {
			$json_response["message"]="There was an error updating password, please try again later.";
			echo json_encode($json_response);
			return;
		}

		$json_response["status"]="OK";
		$json_response["message"]="Password updated successfully.";
		echo json_encode($json_response);
		return;
	}

?>