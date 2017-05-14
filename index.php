<?php 

	session_start();
	global $ERR_MSG,$conn;

	include "lib.php";
	include "connection.php";
	include "model.php";

	$REQ=fetch($_POST,"req");

	db_connect();

	if( fetch($_SESSION,"email_id") == "" && $REQ != 'login' && fetch($_COOKIE,'login_session')!='' ){
		login(fetch($_COOKIE,'login_session'));
	}


	switch ( $REQ ) {
		case "register" :
			register();
			break;
		case "login" :
			login();
			break;
		case "profile" :
			profile();
			break;
		case "update_profile" :
			update_profile();
			break;
		case "change_password" :
			change_password();
			break;
		case "jumma" :
			jumma();
			break;
		case "monthly" :
			monthly();
			break;
	}

	db_close();
	exit;

?>