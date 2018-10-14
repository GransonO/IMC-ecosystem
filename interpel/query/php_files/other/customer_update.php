<?php
include 'core/init.php';

//Verifys user logged in
$USER_ID = $_SESSION['ID'];
	if($USER_ID === null){
		//Show loggin error
	header('Location: ../access/access_error.php');
	
	}else{
		//do nothing
	}

//Get User Details
$User_Data = "SELECT `username` AS Name FROM privileged_users WHERE `ID` = '$USER_ID'";
$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
$username = $U_query['Name'];

if((isset($_POST['phone_no'])===true && empty($_POST['phone_no']) === false)&&(isset($_POST['reg_email'])===true && empty($_POST['reg_email']) === false)){

	$mem_name = $_POST['mem_name'];
	$prev_id = $_POST['prev_id'];
	$phone_no = $_POST['phone_no'];
	$reg_email = $_POST['reg_email'];
	$customer_type = $_POST['customer_type'];
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$update_date = date('Y-m-d H:i:s');
	
		$update_loyalty = "UPDATE `member_loyalty_funds` SET `member_id` = '$phone_no', `member_name` = '$mem_name' WHERE `member_id` = '$prev_id'";
		
		$deal = mysqli_query(Connect_Database(), $update_loyalty)? 1:0 ;
						
	if($deal == 1){
		$update_member = "UPDATE `member` SET `member_id` = '$phone_no', `member_name` = '$mem_name', `phone` = '$phone_no', `email` = '$reg_email', `customer_type` = '$customer_type' WHERE `member_id` = '$prev_id'";
		
		$real = mysqli_query(Connect_Database(), $update_member)? 1:0 ;
		
		if($real == 1){
			
		echo $real;
		}		
	}
	else{
		echo 0;
	}
}
?>
