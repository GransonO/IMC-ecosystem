<?php
include 'core/init.php';
//Verifys user logged in 

//Registration of new members into the DB.
 if((isset($_POST['phone_no'])===true && empty($_POST['phone_no']) === false)&&(isset($_POST['username'])===true && empty($_POST['username']) === false)&&(isset($_POST['reg_password'])===true && empty($_POST['reg_password']) === false)){
	
	date_default_timezone_set('Africa/Nairobi');
	$Trans_date = date('Y-m-d H:i:s');	
	
	$username = $_POST['username'];
	$phone_no = $_POST['phone_no'];
	$reg_email = $_POST['reg_email'];
	$reg_password = $_POST['reg_password'];
	$con_password = $_POST['con_password'];

	if($reg_password !== $con_password){		
		header('Location:../../access/password_error.php');
		exit();
		
	}else{
	$username = sanitize($username);
	$phone_no = sanitize($phone_no);
	$reg_email = sanitize($reg_email);
	$reg_password = sanitize($reg_password);
	$reg_password = md5($reg_password);
		
	$query = mysqli_query(Connect_Database(),"SELECT `phone_no` AS mycount FROM `privileged_users` WHERE `phone_no`= '$phone_no'");
	$row_cnt = mysqli_num_rows($query);

	$equery = mysqli_query(Connect_Database(),"SELECT `email` AS count FROM `privileged_users` WHERE `email`= '$reg_email'");
	$eresult = mysqli_num_rows($equery);  
	//echo "SELECT `phone_no` AS mycount FROM `privileged_users` WHERE `phone_no`= '$phone_no'";
	//echo "SELECT `email` AS count FROM `privileged_users` WHERE `email`= '$reg_email'";
	
 	if($row_cnt !== 0 AND $eresult !== 0){
		//Member exists
		//echo $query.'<br>'.$row_cnt;
		header('Location: ../../access/entry_error.php');
		exit();
	}else{
		//Member does not exist
		//Gets the date time of entry
		date_default_timezone_set('Africa/Nairobi');
		$Entry_date = date('Y-m-d H:i:s');	

		//If the ID doesnt Exist Insert Data to member table 
		$insert_into_user = "INSERT INTO `privileged_users` 
		(`username`,`password`,`phone_no`,`email`,`registered_count`,`created_at`,`created_by`)
		VALUES 
		('$username','$reg_password','$phone_no','$reg_email','0','$Trans_date','ANDROID.SYS.INDULGENCE.MKT')";
									
			if (mysqli_query(Connect_Database(), $insert_into_user)) {
				//Success
				header('Location: ../../login/redirect.php?mode=success');
				exit();
				
			} else {
				//Consult Assistance
				header('Location: ../../access/signup_error.php');
				exit();
		}
	}	 
	}
}
?>