<?php 

	monthly();

	function monthly(){
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