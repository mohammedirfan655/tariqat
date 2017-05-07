<?php 

	global $ERR_MSG,$conn;

	include "lib.php";
	include "connection.php";

	$REQ=fetch($_POST,"req");

	db_connect();

	switch ( $REQ ) {
		case "register" :
			include "register.php";
		case "login" :
			include "login.php";
		case "jumma" :
			include "jumma.php";
		case "monthly" :
			include "monthly.php";
	}

	db_close();
	exit;

?>