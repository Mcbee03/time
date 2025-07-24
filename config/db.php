<?php
	$host  = 'localhost';
	$username  = 'u209391291_dtr';
	$password   = "Nvdcmis@1976";
	$database  = "u209391291_dtr";

	$conn = mysqli_connect($host, $username, $password, $database);

	if(!$conn){
		die("Error: Failed to connect to database!");
	}
?>