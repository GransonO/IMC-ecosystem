<?php
include 'core/init.php';
//Verifys user logged in 

//Registration of new members into the DB.
 if((isset($_POST['firstname'])===true && empty($_POST['firstname'])=== false)
	&&(isset($_POST['lastname'])===true && empty($_POST['lastname']) === false)
	&&(isset($_POST['phone_no'])===true && empty($_POST['phone_no']) === false)
	&&(isset($_POST['reg_email'])===true && empty($_POST['reg_email']) === false)
	&&(isset($_POST['reg_country'])===true && empty($_POST['reg_country']) === false)
	&&(isset($_POST['locality'])===true && empty($_POST['locality']) === false)
	&&(isset($_POST['nearest_town'])===true && empty($_POST['nearest_town']) === false)
	&&(isset($_POST['reg_county'])===true && empty($_POST['reg_county']) === false)
	&&(isset($_POST['box_no'])===true && empty($_POST['box_no']) === false)
	&&(isset($_POST['reg_password'])===true && empty($_POST['reg_password']) === false)
	&&(isset($_POST['area_code'])===true && empty($_POST['area_code']) === false)){
	
	date_default_timezone_set('Africa/Nairobi');
	$Trans_date = date('Y-m-d H:i:s');	

	$firstname = sanitize($_POST['firstname']);
	$lastname = sanitize($_POST['lastname']);
	$phone_no = sanitize($_POST['phone_no']);
	$reg_email = sanitize($_POST['reg_email']);
	$reg_country = strtoupper(sanitize($_POST['reg_country']));
	$locality = strtoupper(sanitize($_POST['locality']));
	$nearest_town = strtoupper(sanitize($_POST['nearest_town']));
	$reg_county = sanitize($_POST['reg_county']);
	$box_no = strtoupper(sanitize($_POST['box_no']));
	$area_code = sanitize($_POST['area_code']);
	$reg_password = sanitize($_POST['reg_password']);
	$prof_image = sanitize($_POST['prof_image']);
		
	$query = mysqli_query(Connect_Database(),"SELECT `email_address` AS my_mail FROM `users_profile` WHERE `email_address`= '$reg_email'");
	$result = (mysqli_fetch_assoc($query));
	$result =  $result['my_mail'];
	
	if($result){
		//Member exists
		$result = 1;
	}else{
		//Member does not exist
		//Gets the date time of entry
		date_default_timezone_set('Africa/Nairobi');
		$reg_date = date('Y-m-d H:i:s');	
		$sha1_no = date('mHYis');
		$teacher_id = sha1($sha1_no);
		$_SESSION['teacher_id'] = $teacher_id;
		//If the ID doesn't Exist Insert Data to member table 
		$insert_into_user_profiles = "INSERT INTO `users_profile` 
		(`registered_date`, `teacher_id`, `first_name`, `last_name`, `phone_no`, `email_address`, `resident_country`, `location`, `area_code`, `address_box`, `town`, `county`, `user_pass_id`,`prof_image`, `last_login_date`, `last_update_date`)
		VALUES ('$reg_date', '$teacher_id', '$firstname', '$lastname', '$phone_no', '$reg_email', '$reg_country', '$locality', '$area_code', '$box_no', '$nearest_town', '$reg_county', MD5('$reg_password'),'$prof_image', NULL,NULL);";
		
									
			if (mysqli_query(Connect_Database(), $insert_into_user_profiles)) {
				//Success
				$result = 2;
				//exit();
				
			} else {
				//Consult Assistance
				$result = 0;
				//exit();
			}
	}
	
	echo $result;
	//echo $insert_into_user_profiles;
}
?>