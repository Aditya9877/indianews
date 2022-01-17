<?php
	session_start();
	session_unset($_SEESION['user_id']);
	session_destroy();
	header('location:log-in.php');
?>