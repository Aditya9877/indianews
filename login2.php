<?php 
	ob_start();
	session_start();
	error_reporting(0);
	$host = "localhost";
	$username = "root";
	$password = "";
	$database = "indianews";
	$conn = mysqli_connect($host, $username, $password, $database);
	if(!$conn){
		die('Please check database connection.');
	} else {
		// echo "connected";
	}
	if(isset($_get['get']))
 ?>