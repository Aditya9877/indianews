<?php
	include_once '../includes/db.php';

	if(isset($_POST['register'])){
		$name = $_POST['name'];
		$dob = $_POST['dob'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$confirm_password = $_POST['confirm_password'];
		$phone_no = $_POST['number'];
		if($name == '' || $email == '' || $password == '' || $confirm_password == '' || $dob == ''|| $phone_no ==''){
			echo "All fields are required";
		} else {
			if($password != $confirm_password){
				echo "Password not matched";	
			} else {
				$check = mysqli_query($conn, "SELECT * FROM users WHERE email='".$email."'");
				if(mysqli_num_rows($check) > 0){
					echo "User already exist.";
				} else{
					$result = mysqli_query($conn,"INSERT INTO users SET name='".$name."', email='".$email."', password='".md5($password)."', dob='".$dob."',phone_no='".$phone_no."'");
					if($result){
						echo "Account created successfully";
					} else {
						echo "An error occured, please try again";
					}
				}
			}
		}
	}
?>
<a href="<?= BASE_URL ?>register.php">Go back</a>