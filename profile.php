<?php 

	profile();

	function profile(){

		global $ERR_MSG;
		$json_response=array();
		$json_response['status']="ERROR";
		$json_response["logged_out"]=false;

		$email_id=fetch($_POST,'email_id');

		$resp=execute("SELECT name,email_id,mobile FROM tUser WHERE `email_id`='$email_id'");
		if ( $resp[0]['STATUS'] == "ERROR" ) {
			$json_response["message"]="Error occured while fetching profile details.";
			echo json_encode($json_response);
			return;
		}

		$json_response["status"]="OK";
		if ( $resp[0]['NROWS'] == 0 ) {
			$json_response["message"]="It seems your profile has been modified, please login again to continue.";
			$json_response["logged_out"]=true;
			echo json_encode($json_response);
			return;
		}

		$json_response["row"]=$resp[0];
		echo json_encode($json_response);
		return;
	}

?>