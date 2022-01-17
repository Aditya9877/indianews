<?php
	include '../includes/db.php';
	include '../includes/constants.php';
	session_unset($_SESSION['user_id']);
	session_destroy();
	header('LOCATION:'.BASE_URL.'log-in.php');
	echo BASE_URL;
?>