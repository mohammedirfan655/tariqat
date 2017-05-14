<?php 

	function db_connect() {
		global $conn,$ERR_MSG;

		// New Connection
		$conn = new mysqli("localhost","irfan","irfan1233","tariqat");

		// Check for errors
		if(mysqli_connect_errno()){
			$ERR_MSG="Error connecting to DB: ".mysqli_connect_error();
		}
	}

	// Close connection
	function db_close() {
		global $conn;

		$conn->close();
	}

	function execute($query,$DML=false){
		global $ERR_MSG;
		global $conn;

		// Reset result set before starting
		$resp = array("STATUS"=>"ERROR");	// For DMLs
		$resp[0]['STATUS']="ERROR";			// For Selects
		$ERR_MSG="There was an error proccessing your request. Please check and try again";

		// INIT STATEMENT
		if ( !$stmt = mysqli_stmt_init($conn) ) {
			$resp['SQL_ERROR_CODE']=mysqli_errno($conn);
			return $resp;
		}

		// PREPARE
		if ( !mysqli_stmt_prepare($stmt,$query) ) {
			$resp['SQL_ERROR_CODE']=mysqli_errno($conn);
			return $resp;
		}

		// EXECUTE 
		$qry_exec_time=microtime(true);
		$status=mysqli_stmt_execute($stmt);
		$qry_exec_time=number_format(microtime(true)-$qry_exec_time,4);

		if ( !$status ) {
			$resp['SQL_ERROR_CODE']=mysqli_errno($conn);
			mysqli_stmt_close($stmt);			// Close statement
			return $resp;
		}

		if ($DML) {
			unset($resp[0]);
			$error_message="";
			$resp["STATUS"]="OK";
			$resp["EXECUTE_STATUS"]=$status;
			$resp["NROWS"]=$conn->affected_rows;
			$resp["INSERT_ID"]=$conn->insert_id;
			mysqli_stmt_close($stmt);			// Close statement
			return $resp;
		}


		// SELECT
		$result_set = mysqli_stmt_result_metadata($stmt);
		while ( $field = mysqli_fetch_field($result_set) ) {
			$parameters[] = &$row[$field->name];
		}

		// BIND OUTPUT
		if ( !call_user_func_array(array($stmt, 'bind_result'), refValues($parameters))) {
			$resp[0]['SQL_ERROR_CODE']=mysqli_errno($conn);
			mysqli_free_result($result_set);	// Close result set
			mysqli_stmt_close($stmt);			// Close statement
			return $resp;
		}

		// FETCH DATA
		$i=0;
		while ( mysqli_stmt_fetch($stmt) ) {  
			$x = array();
			foreach( $row as $key => $val ) {  
				$x[$key] = $val;  
			}
			$results[] = $x; 
			$i++;
		}
		$results[0]["NROWS"]=$i;

		$ERR_MSG="";					// Reset Error message
		$results[0]["STATUS"]="OK";			// Reset status
		mysqli_free_result($result_set);	// Close result set
		mysqli_stmt_close($stmt);			// Close statement

		return  $results;
	}

	function refValues($arr){
		if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
		{
			$refs = array();
			foreach($arr as $key => $value)
				$refs[$key] = &$arr[$key];
			return $refs;
		}
		return $arr;
	}

	
?>