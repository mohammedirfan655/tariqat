<?php 

	function register(){

		global $ERR_MSG;
		$json_response=array();
		$json_response['status']="ERROR";

		$email_id=fetch($_POST,"email_id");
		$password=fetch($_POST,"password");
		$name=fetch($_POST,"name");
		$age=fetch($_POST,"age");
		$qualification=fetch($_POST,"qualification");
		$occupation=fetch($_POST,"occupation");
		$silsila_start=fetch($_POST,"silsila_start");
		$address=fetch($_POST,"address");
		$mobile=fetch($_POST,"mobile");

		// Validate ALL parameters
		if (!validate("Email ID",$email_id,5,100,"EMAIL") ||
			!validate("Password",$password,5,100,"PASSWORD") ||
			!validate("Name",$name,5,100,"varchar") ||
			!validate("Age",$age,1,3,"int") ||
			!validate("Qualification",$qualification,1,20,"varchar") ||
			!validate("Occupation",$occupation,1,30,"varchar") ||
			!validate("Silsila Join date",$silsila_start,0,10,"date") ||
			!validate("Address",$address,10,500,"varchar") ||
			!validate("mobile",$mobile,10,10,"int") ) {
			$json_response['message']=$ERR_MSG;
			echo json_encode($json_response);
			return;
		}

		$resp=execute(
						"INSERT INTO tUser ( `email_id`,`name`,`mobile`,`password`,`age`,`qualification`,`occupation`,`silsila_start`,`address` ) VALUES ( '$email_id','$name','$mobile','$password','$age','$qualification','$occupation','$silsila_start','$address' )"
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

	function login($login_session=""){

		global $ERR_MSG;
		$json_response=array();
		$json_response['status']="ERROR";

		$email_id=fetch($_POST,"email_id");
		$password=fetch($_POST,"password");

		// Logout if you have not already loggedin
		logout();

		if ( $login_session != "" ) {
			// Validate ALL parameters
			if (!validate("Login Session",$login_session,5,100,"varchar") ) {
				$json_response['message']=$ERR_MSG;
				echo json_encode($json_response);
				return;
			}
			$ROW=execute("SELECT * FROM tUser WHERE `login_session`='$login_session'");
		} else {
			// Validate ALL parameters
			if (!validate("Email ID",$email_id,5,100,"EMAIL") ||
				!validate("Password",$password,5,100,"PASSWORD") ) {
				$json_response['message']=$ERR_MSG;
				echo json_encode($json_response);
				return;
			}
			$ROW=execute("SELECT * FROM tUser WHERE `email_id`='$email_id' AND `password`='$password'");
		}
		if ( $ROW[0]['STATUS'] == "ERROR" ) {
			if ( $login_session == "" )
				$json_response["message"]="Error occured while logging in, please try again later.";
			else
				$json_response["message"]="Error occured while performing auto login, please try again later.";

			echo json_encode($json_response);
			return;
		}
		if ( $ROW[0]['NROWS'] == 0 ) {
			$json_response["message"]="Please provide valid credentials.";
			echo json_encode($json_response);
			return;
		}

		session_regenerate_id(true);

		$_SESSION['logged_in']=1;
		$_SESSION['email_id']=$ROW[0]["email_id"];
		$_SESSION['user_id']=$ROW[0]["user_id"];

		if ( $login_session == "" ) {
			$resp=execute("UPDATE tUser SET `login_session`='".md5_encrypt($ROW[0]['user_id']."' WHERE user_id='".$ROW[0]['user_id']."'")
			if ( $resp['STATUS'] != "OK" ) {
				$json_response['message']='There was an error updating the user session.';
				echo json_encode($json_response);
				return;
			}
			setcookie('login_session', md5_encrypt($ROW[0]["user_id"]), time() + (86400 * 30), "/");
		}

		$json_response["user_row"]=$ROW[0];
		$json_response["status"]="OK";
		echo json_encode($json_response);
		return;
	}

	function logout() {

		setcookie('login_session', null, -1, '/');
		$_SESSION="";
		$_SESSION['logged_in']=0;
		$_SESSION['email_id']="";
		$_SESSION['user_id']="";

	}

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

	function monthly(){

		global $ERR_MSG;
		$json_response=array();
		$json_response['status']="ERROR";

		$name="Huzaifah";
		$email_id="md.huzaifah1218@gmail.com";
		$mobile="8660295286";

		$field1=fetch($_POST,"field1");
		$field2=fetch($_POST,"field2");
		$field3=fetch($_POST,"field3");
		$field4=fetch($_POST,"field4");
		$field5=fetch($_POST,"field5");
		$field6=fetch($_POST,"field6");
		$field7=fetch($_POST,"field7");
		$field8=fetch($_POST,"field8");
		$field9=fetch($_POST,"field9");
		$field10=fetch($_POST,"field10");
		$field11=fetch($_POST,"field11");
		$field12=fetch($_POST,"field12");
		$field13=fetch($_POST,"field13");
		$field14=fetch($_POST,"field14");

		// Validate ALL parameters
		if (!validate("Field1",$field1,1,1000,"varchar") ||
			!validate("Field2",$field2,1,1000,"varchar") ||
			!validate("Field3",$field3,1,1000,"varchar") ||
			!validate("Field4",$field4,1,1000,"varchar") ||
			!validate("Field5",$field5,1,1000,"varchar") ||
			!validate("Field6",$field6,1,1000,"varchar") ||
			!validate("Field7",$field7,1,1000,"varchar") ||
			!validate("Field8",$field8,1,1000,"varchar") ||
			!validate("Field9",$field9,1,1000,"varchar") ||
			!validate("Field10",$field10,1,1000,"varchar") ||
			!validate("Field11",$field11,1,1000,"varchar") ||
			!validate("Field12",$field12,1,1000,"varchar") ||
			!validate("Field13",$field13,1,1000,"varchar") ||
			!validate("Field14",$field14,1,1000,"varchar") ) {
			$json_response['message']=$ERR_MSG;
			echo json_encode($json_response);
			return;
		}

		$from="md.huzaifah1218@gmail.com";
//		$from="mohammedirfan655@gmail.com";
		$to="md.huzaifah1218@gmail.com";
//		$to="mohammedirfan655@gmail.com";

		ob_start();
		include('monthly_data.html');
		$message=ob_get_contents();
		ob_get_clean();


		$resp=send_email($to,"Monthly Data",$message,$from,"",$to);
		if ( !$resp ) {
			$json_response["message"]="There was an error sending the email.";
			echo json_encode($json_response);
			return;
		}

		$json_response["status"]="OK";
		$json_response["message"]="Formed submitted successfully.";
		echo json_encode($json_response);
		return;
	}

	
?>