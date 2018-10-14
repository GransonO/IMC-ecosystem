<?php
//include the init file for all functions
include 'core/init.php';
		
if(empty($_POST)===false){
					
		$register_first_name = $_POST['register_first_name'];
		$register_last_name = $_POST['register_last_name'];
		$register_phone_number = $_POST['register_phone_number'];
		$register_email_address = $_POST['register_email_address'];	
		$state = $_POST['state'];	
		$destination_description = $_POST['destination_description'];	
		$weekend_entry = $_POST['weekend_entry'];	
		$experience_description = $_POST['experience_description'];	
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$create_date = date('Y-m-d H:i:s');
						
		$insert_into_members_details = "INSERT INTO `member_details_tb` 
		(`date`,`first_name`, `last_name`, `phone_number`, `email_address`, `status`, `fav_destinations`, `weekend_getaway`, `visit_feedback`) VALUES 
		('$create_date', '$register_first_name', '$register_last_name', '$register_phone_number', '$register_email_address', '$state', '$destination_description', '$weekend_entry', '$experience_description');";
		
		$zeal = mysqli_query(Connect_Database(), $insert_into_members_details)? 2:0 ;
				
		if($zeal === 2){
			//Posting success
			echo 2;
			
		}else{
			//posting failed
			echo 0;
		} 

}
?>