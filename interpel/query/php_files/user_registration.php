<?php
//include the init file for all functions
include 'core/init.php';
		
if(empty($_POST)===false){

$profile = $_SESSION['profile']; 
$invitor_id = $_SESSION['assignee']; 
		
		$username = $_POST['register_username'];
		$password = $_POST['register_pass'];
		$email = $_POST['register_email'];			
		$created_pass = md5($password);
		
		$invitor_query = "SELECT `username` as name FROM `privileged_users` WHERE `password` = '$invitor_id';";
		$invitor_result = mysqli_fetch_assoc(mysqli_query(Connect_Database(), $invitor_query));
		$invitor_name = $invitor_result['name'];
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$create_date = date('Y-m-d H:i:s');
				
				$insert_into_users = "INSERT INTO `privileged_users` 
				(`username`,`password`, `email`,`profile`,`created_at`, `created_by`, `redeemer`)
				VALUES ('$username', '$created_pass', '$email','$profile','$create_date', '$invitor_name','0');";
				
				$zeal = mysqli_query(Connect_Database(), $insert_into_users)? 1:0 ;
				
			if($zeal === 1){
				//Posting success
				echo 1;
			}else{
				//posting failed
				echo 0;
			} 
}		
?>