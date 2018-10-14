<?php
//include the init file for all functions
include 'init.php';
		
if(empty($_POST)===false){
	
		$register_first_name = $_POST['name'];
		$register_phone_number = $_POST['phone'];
		$register_email_address = $_POST['mail'];	
		$country = $_POST['country'];	
		$identification = $_POST['identification'];	
		$city = $_POST['city'];	
		$value = $_POST['mybox'];	
		
		$arr_date = $_SESSION['arrive'];
		$dep_date = $_SESSION['depart'];
		$person = $_SESSION['persons'];
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$create_date = date('Y-m-d H:i:s');
		
		$insert_into_campaign_table = "INSERT INTO `campaign_table` (`date_time`, `full_name`, `phone`, `email`, `country`, `city`, `identification`, `value`, `arrive`, `depart`, `presons`) VALUES ('$create_date', '$register_first_name', '$register_phone_number ', '$register_email_address', '$country', '$city', '$identification', '$value','$arr_date', '$dep_date', '$person');";
		
		$zeal = mysqli_query(Connect_Database(), $insert_into_campaign_table)? 2:0 ;
				
		if($zeal === 2){
			//Posting success
			echo 2;
			
		}else{
			//posting failed
			echo 0;
		} 

}
?>