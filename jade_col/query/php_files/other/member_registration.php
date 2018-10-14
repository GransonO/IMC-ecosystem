<?php
include 'core/init.php';
//Verifys user logged in 

$USER_ID = $_SESSION['ID'];


//Registration of new members into the DB.
if((isset($_POST['phone_no'])===true && empty($_POST['phone_no']) === false)&&(isset($_POST['mem_name'])===true && empty($_POST['mem_name']) === false)){
	
	$mem_name = $_POST['mem_name'];
	$phone_no = $_POST['phone_no'];
	$reg_email = $_POST['reg_email'];
	$gender = $_POST['gender'];
	$service_type = $_POST['service_type'];
	$customer_type = $_POST['customer_type'];
	$customer_comments = $_POST['customer_comments'];

	$mem_name = sanitize($mem_name);
	$phone_no = sanitize($phone_no);
	$reg_email = sanitize($reg_email);
	$gender = sanitize($gender);
	$service_type = sanitize($service_type);
	$customer_type = sanitize($customer_type);
	$customer_comments = sanitize($customer_comments);
	
	//Get User Details
	$User_Data = "SELECT `username` AS Name, `registered_count` AS registered_count FROM privileged_users WHERE `password` = '$USER_ID'"; 
	$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
	$username = $U_query['Name'];
	$registered_count = $U_query['registered_count'];
	
	$query = mysqli_query(Connect_Database(),"SELECT `member_id` AS count FROM `member` WHERE `member_id`= '$phone_no'");
	$result = (mysqli_fetch_assoc($query));
	$result =  $result["count"];
	
	if($result){
		//Member exists
		$result = 2;
	}else{
		//Member does not exist
		//Gets the date time of entry
		date_default_timezone_set('Africa/Nairobi');
		$Entry_date = date('Y-m-d H:i:s');	

		//If the ID doesnt Exist Insert Data to member table 
		$insert_into_member = "INSERT INTO `member` 
		(`is_active`, `created_at`, `created_by`, `is_deleted`, `member_id`, `member_name`,`phone`,`email`,`service_point`, `customer_type`, `gender`, `date_deleted`, `deleted_by`, `reason_deleted`, `row_comment`)
		VALUES 
		('1', '$Entry_date','$username', '0', '$phone_no', '$mem_name', '$phone_no', '$reg_email', '$service_type', '$customer_type', '$gender', NULL,'TT', 'TT', '$customer_comments')";
		
		$insert_into_loyalty = "INSERT INTO `member_loyalty_funds`
		(`is_active`, `created_at`, `created_by`, `is_deleted`, `member_id`, `member_name`, `loyalty_value_earned`, `total_points_available`, `total_points_redeemed`, `redemptions_count`, `expired_loyalty`, `last_redeem_date`, `redeeemed_by`, `is_credit`, `max_trans_date`, `date_deleted`, `deleted_by`, `reason_deleted`, `row_comment`)
		VALUES ('0', '$Entry_date', '$username', '0', '$phone_no', '$mem_name', '0', '0', '0', '0', '0', NULL, 'RR', '0', NULL, NULL, 'ww', 'ww', 'ww');";
		
		$Total_count = $registered_count + 1 ;
		
		$update_count = "UPDATE `privileged_users` SET  `registered_count` = '$Total_count' WHERE `password` = '$USER_ID'";
		$real = mysqli_query(Connect_Database(), $update_count)? 1:0 ;
							
		//echo $insert_into_loyalty.'</br>'.$insert_into_member_contact.'</br>'.$insert_into_member;
		//inserts into  table 
			if (mysqli_query(Connect_Database(), $insert_into_member) && mysqli_query(Connect_Database(), $insert_into_loyalty)) {
				
				$result = 1;
				
			} else {
				$result = $insert_into_loyalty;
		}
	}
	
	echo $result;
}
?>