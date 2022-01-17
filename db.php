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

	function parent_cat_name($cat_id, $conn){
		$query = mysqli_query($conn,"SELECT * FROM post_categories WHERE status = '1' and id='".$cat_id."'");
        if(mysqli_num_rows($query) > 0){
            $cat = mysqli_fetch_array($query);
            return $cat['name'];
        }
	}

	function child_cat_name($cat_id, $conn){
		$query = mysqli_query($conn,"SELECT * FROM post_sub_category WHERE status = '1' and id='".$cat_id."'");
        if(mysqli_num_rows($query) > 0){
            $cat = mysqli_fetch_array($query);
            return $cat['name'];
        }
	}

	function author_name($user_id, $conn){
		$query = mysqli_query($conn,"SELECT * FROM users WHERE status = '1' and id='".$user_id."'");
        if(mysqli_num_rows($query) > 0){
            $cat = mysqli_fetch_array($query);
            return $cat['name'];
        }
	}
?>