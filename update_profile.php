<?php 

	update_profile();

	function update_profile(){

		global $ERR_MSG;
		$json_response=array();
		$json_response['status']="ERROR";

		$email_id=fetch($_POST,"email_id");
		$name=fetch($_POST,"name");
		$mobile=fetch($_POST,"mobile");

		// Validate ALL parameters
		if (!validate("Email ID",$email_id,5,100,"EMAIL") ||
			!validate("Name",$name,5,100,"varchar") ||
			!validate("mobile",$mobile,10,10,"varchar") ) {
			$json_response['message']=$ERR_MSG;
			echo json_encode($json_response);
			return;
		}

		$resp=execute(
						"UPDATE tUser SET `name`='$name',`mobile`='$mobile' WHERE `email_id`='$email_id'"
					,true);
		if ( $resp['STATUS'] == "ERROR" ) {
			$json_response["message"]="There was an error updating profile, please try again later.";
			echo json_encode($json_response);
			return;
		}

		$json_response["status"]="OK";
		$json_response["message"]="Your profile has successfully been updated.";
		echo json_encode($json_response);
		return;
	}

?>