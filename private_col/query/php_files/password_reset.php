<?php
include 'core/init.php';

if((isset($_POST['reset_password'])===true && empty($_POST['reset_password'])=== false)){
		
			$the_password = $_SESSION['gen_pass'];
			$user = $_SESSION['reset_user'];
			$email = $_SESSION['reset_mail'];
			
			$old_pass =md5($the_password);
			$new_pass = $_POST['reset_password'];
				
			$password = md5($new_pass);
			$update_query = "UPDATE `privileged_users` SET `password` = '$password' WHERE `email` = '$email' AND `username` = '$user'";
			$update_pass = (mysqli_query(Connect_Database(),$update_query))? 1:0;
		
			if($update_pass === 1){
			
			echo 2;
			
			}else{
			//reset error
			echo 1;
			
			}
	} 

?>