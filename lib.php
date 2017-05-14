<?php 

	function fetch($ARR,$index) {
		if (isset($ARR[$index])) { 
			return $ARR[$index]; 
		} else {
			return "";
		}
	}


	function send_email($to,$subject,$message,$from="",$cc="",$reply_to="") {

		if (!$from ) $from = EMAIL_FROM;

		$subject  = "[Tariqat] $subject";
		$headers  = "From: $from\r\n";
		$headers .= "CC: $cc\r\n";   
		$headers .= "Content-type: text/html\r\n";

		require_once 'mailer/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPAuth = true; // enable SMTP authentication
		//$mail->SMTPSecure = "ssl"; // sets the prefix to the servier
		$mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
		$mail->Port = 587; // set the SMTP port for the GMAIL server
		$mail->Username = "mohammedirfan655@gmail.com"; // GMAIL username
		$mail->Password = "Insert12#"; // GMAIL password

		//Typical mail data
		$to_array = array();
		$to_array = explode(',',$to);
		for( $j=0 ; $j<count($to_array) ; $j++ ) {
			$mail->AddAddress($to_array[$j]);
		}
		$mail->SetFrom($from);
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->IsHTML(true);

		try{
			$mail->Send();
			return true;
		} catch(Exception $e){
			return false;
		}

		return mail($to, $subject, $message, $headers);
	}

	function validate($name,$value,$minlen,$maxlen,$datatype="",$min_val="",$max_val="",$regexp="") {

		global $ERR_MSG;

		$resp=true;

		// If the value is empty and the field is not mandatory, then return
		if ( (!isset($minlen) || $minlen == 0) && $value == "" ) {
			return true;
		}

		// Empty Check
		// Changed to === to ensure 0 does not fail 
		if ( isset($minlen) && $minlen > 0 && $value === "" ) {
			$ERR_MSG=$name." cannot be empty."; 
			return false;
		}

		//echo "count($value)=[".preg_match("/^[0-9]+$/","12344a4")."]<br>";
		// MIN LEN check
		if ( isset($minlen) && strlen($value) < $minlen ) {
			$ERR_MSG=$name." should be atleast ".$minlen." characters long."; 
			return false;
		}

		// MAX LEN check
		if ( isset($maxlen) && strlen($value) > $maxlen ) {
			$ERR_MSG=$name." cannot be longer than ".$maxlen." characters."; 
			return false;
		}

		// CUSTOM REGEXP check
		if ( isset($regexp) && !preg_match("/$regexp/",$value) ) {
			$ERR_MSG=$name." is not valid."; 
			return false;
		}

		// MIN value check
		if( ($min_val !== '' && $value < $min_val) ) {
			$ERR_MSG=$name." cannot be less than ".$min_val."."; 
			return false;
		}

		// MAX value check
		if( ($max_val !== '' && $value > $max_val) ) {
			$ERR_MSG=$name." cannot be greater than ".$max_val."."; 
			return false;
		}
		// STANDARD DATATYPES check
		if ( isset($datatype) ) {
			switch ($datatype) {
				case "int":
					if ( filter_var($value, FILTER_VALIDATE_INT) === false  ) {
						$ERR_MSG=$name." should contain only digits."; 
						return false;
					} 
					break;
				case "decimal":
					if ( filter_var($value, FILTER_VALIDATE_FLOAT) === false ) {
						$ERR_MSG=$name." should contain only digits."; 
						return false;
					} 
					break;
				case "char": // anything
				case "varchar": // anything
				case "text": // anything
					return true;
					break;
				case "bigint":
				case "tinyint":
					if (!preg_match("/^[0-9]+$/",$value)) {
						$ERR_MSG=$name." should contain only digits."; 
						return false;
					} 
					break;
				case "date":
					$arr=preg_split("/-/",$value); // splitting the array
					$yy=fetch($arr,0); // first element of the array is month
					$mm=fetch($arr,1); // second element is date
					$dd=fetch($arr,2); // third element is year
					if( $dd == "" || $mm == "" || $yy == "" || !checkdate($mm,$dd,$yy) ){
						$ERR_MSG=$name." is not a valid date, should be of the format YYYY-MM-DD"; 
						return false;
					}
					break;
				case "PASSWORD":
					if (!preg_match("/^[a-zA-Z\-_0-9]+$/",$value)) {
						$ERR_MSG=$name." can contain only alphabets,numbers,'-' and '_'."; 
						return false;
					} 
					break;
				case "SIMPLE_STRING": // can only have alphabets, spaces, dots, -'s or +
					if (!preg_match("/^[a-zA-Z0-9\.\s\-\+]+$/",$value)) {
						$ERR_MSG=$name." should contain only alphabets, numbers, spaces '.', '-' or '+'."; 
						return false;
					} 
					break;
				case "EMAIL":
					if ( filter_var($value, FILTER_VALIDATE_EMAIL) == false ) {
						$ERR_MSG=$name." is not valid, should be of the format abc@xyz.com."; 
						return false;
					}
					break;
				case "MOBILE":
					if (!preg_match("/^[0-9]+$/",$value)) {
						$ERR_MSG=$name." is not valid, should be of the format 919123456789"; 
						return false;
					} 
					break;
				case 'FILENAME':
					if ($value != basename($value) || !preg_match("/^[a-zA-Z0-9_\.]+$/",$value) || !preg_match('/^(?:[a-z0-9_-]|\.(?!\.))+$/iD', $value)) {
						$ERR_MSG="Invalid $name";
						return false;
					}
					break;
				default:
					$ERR_MSG=$name." is not valid. Please re enter."; 
					return false;
			}
		}

		return true;
	}

	function md5_encrypt($data){
		return md5("TARIQAT".$data);
	}


?>