<?php
	include '../includes/db.php';
	include '../includes/constants.php';
	if(isset($_POST['login'])){	
		$email = $_POST['email'];
		$password = md5($_POST['password']);
		if($email != '' && $password != ''){
			$check = mysqli_query($conn, "SELECT * FROM users WHERE email='".$email."' && password='".$password."' and status='1'");
			if(mysqli_num_rows($check) > 0){
				$data = mysqli_fetch_assoc($check);
				$check2 = mysqli_query($conn, "SELECT * FROM users WHERE varified='1' and id='".$data['id']."'");
				if(mysqli_num_rows($check2) > 0){
					$data2 = mysqli_fetch_assoc($check2);
					$_SESSION['user_id'] = $data2['id'];
					$_SESSION['name'] = $data2['name'];
					header('LOCATION: '.BASE_URL.'index.php');
				} else {
					echo "Your account is not approved by the admin. Please try again after some time or contact to site admin.";	
				}
			} else{
				echo "Username or password is incorrect.";
			}
		} else {
			echo 'Username and password field is required';	
		}	
	}
?><br />
<a href="<?= BASE_URL ?>log-in.php">Go back</a>