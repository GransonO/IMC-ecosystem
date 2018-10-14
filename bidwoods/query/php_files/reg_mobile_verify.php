<?php
include 'core/init.php';
//Verifys if number is present in DB.
if(isset($_POST['phone_no'])===true && empty($_POST['phone_no']) === false){
	$phone_no = $_POST['phone_no'];

	$phone = sanitize($phone_no);
	$query = mysqli_query(Connect_Database(),"SELECT `member_id` AS count FROM `member` WHERE `member_id`= '$phone'");
	$result = (mysqli_fetch_assoc($query));
	$result = ($result["count"])? $result["count"]: 0;
	
	echo $result;
}
?>