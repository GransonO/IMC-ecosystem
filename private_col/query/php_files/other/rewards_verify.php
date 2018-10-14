<?php
include 'core/init.php';
//Verifys if number is present in DB.
if(isset($_POST['phone_no'])===true && empty($_POST['phone_no']) === false){
	$phone_no = $_POST['phone_no'];

	$phone = sanitize($phone_no);
	$query = mysqli_query(Connect_Database(),"SELECT `member_id` AS id,`member_name` as name,`total_points_available` as points FROM `member_loyalty_funds` WHERE `member_id`= '$phone'");
	$result = (mysqli_fetch_assoc($query));
	$id = ($result["id"])? $result["id"]: 0;
	$name = ($result["name"])? $result["name"]: 0;
	$points = ($result["points"])? $result["points"]: 0;
	
	//Get the email
	$email_query = mysqli_query(Connect_Database(),"SELECT `email` AS email_address FROM `member` WHERE `member_id`= '$phone'");
	$email_result = (mysqli_fetch_assoc($email_query));
	$email_address = ($email_result["email_address"])? $email_result["email_address"]: "--";
	
	if($id != 0){
	$arr = array('id' => $id, 'name' => $name, 'email' => $email_address, 'points' => $points);
	
	$rewJSON = json_encode($arr);

	echo $rewJSON;
	
	}else{
	
	echo $name;	
	}
}
?>