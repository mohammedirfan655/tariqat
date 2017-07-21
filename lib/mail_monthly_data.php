<?php 

	session_start();
	global $ERR_MSG,$conn;

	include "../lib.php";
	include "../connection.php";
	include "../model.php";
	define("FROM","mohammedirfan655@gmail.com");
//	define("FROM","talha.tariqat@gmail.com");

	db_connect();
	$ROW=array();
	$ROW[0]=array();

	$name="Huzaifah";
	$email_id="md.huzaifah1218@gmail.com";
//	$email_id="mohammedirfan655@gmail.com";
	$mobile="8660295286";

	$USER_ROW=execute(
					"SELECT `user_id`,email_id,name,mobile,age,qualification,occupation,silsila_start,address FROM tUser WHERE 1"
				,false);
	if ( $USER_ROW[0]['STATUS'] == "ERROR" || $USER_ROW[0]['NROWS'] == 0 ) {
		exit;
	}

	for ( $i=0;$i<$USER_ROW[0]['NROWS'];$i++ ) { 

		$ROW=execute(
						"SELECT `type`,value,user_id,created_dt FROM tData WHERE user_id=".$USER_ROW[$i]['user_id']." AND is_sent=0 ORDER BY type,created_dt"
					,false);
		$USER_ROW[$i]['data']=$ROW;
	}
	for ( $j=0;$j<$USER_ROW[0]['NROWS'];$j++ ) {

		if($USER_ROW[$j]['data'][0]['NROWS']==0) continue;
		$from="tariqat.org@gmail.com";
//		$from="mohammedirfan655@gmail.com";
		$to="tariqat.org@gmail.com";
//		$to="mohammedirfan655@gmail.com";

//		continue;
		ob_start();
		include('monthly_data.html');
		$message=ob_get_contents();
		ob_get_clean();

		$resp=send_email($to,"Monthly Report of ".$USER_ROW[$j]['email_id'],$message,$from,"",$to);
		if ( !$resp ) {
			echo "There was an error sending the email.";
		} else {
			$resp=execute(
							"UPDATE tData SET is_sent=1 WHERE user_id=".$USER_ROW[$j]['user_id']." AND is_sent=0"
						,true);
			echo "Formed submitted for email_id=".$USER_ROW[$j]['email_id']." successfully\n";
		}
	}
	db_close();
	exit;

?>