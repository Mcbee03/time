<?php
	$host  = 'localhost';
	$username  = 'root';
	$password   = "";
	$database  = "dtr";

	$conn = mysqli_connect($host, $username, $password, $database);

	if(!$conn){
		die("Error: Failed to connect to database!");
	}
?>