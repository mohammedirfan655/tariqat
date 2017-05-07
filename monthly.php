<?php 

	monthly();

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

//		$from="md.huzaifah1218@gmail.com";
		$from="mohammedirfan655@gmail.com";
//		$to="md.huzaifah1218@gmail.com";
		$to="mohammedirfan655@gmail.com";

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