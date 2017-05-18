<?php 

	session_start();
	global $ERR_MSG,$conn;

	include "lib.php";
	include "connection.php";
	include "model.php";
//	define("FROM","mohammedirfan655@gmail.com");
	define("FROM","talha.tariqat@gmail.com");

	$REQ=fetch($_POST,"req");

	db_connect();

	if( fetch($_SESSION,"email_id") == "" && $REQ != 'login' && $REQ != 'register' && fetch($_COOKIE,'login_session')!='' ){
		login(fetch($_COOKIE,'login_session'));
	}


	switch ( $REQ ) {
		case "register" :
			register();
			break;
		case "login" :
			login();
			break;
		case "forgot_password" :
			forgot_password();
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
		case "daily" :
			daily();
			break;
		case "monthly" :
			monthly();
			break;
		case "jumma" :
			jumma();
			break;
		case "app_update_check" :
			app_update_check();
			break;
	}

	db_close();
	exit;

?>