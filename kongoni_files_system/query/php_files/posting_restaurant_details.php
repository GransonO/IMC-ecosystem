<?php
include 'core/init.php';

//Registration of new members into the DB.
 if((isset($_POST['folio'])===true && empty($_POST['folio']) === false)
	&&(isset($_POST['guest_name'])===true && empty($_POST['guest_name']) === false)
	&&(isset($_POST['restaurant_check_no'])===true && empty($_POST['restaurant_check_no']) === false)
	&&(isset($_POST['restaurant_amount'])===true && empty($_POST['restaurant_amount']) === false)){

	$folio = sanitize($_POST['folio']);
	$gr_card = sanitize($_POST['gr_card']);
	$guest_name = sanitize($_POST['guest_name']);
	$restaurant_date = sanitize($_POST['restaurant_date']);
	$restaurant_check_no = sanitize($_POST['restaurant_check_no']);
	$restaurant_description = trim(sanitize($_POST['restaurant_description']));
	$restaurant_amount = sanitize($_POST['restaurant_amount']);
	$restaurant_pay_type = sanitize($_POST['restaurant_pay_type']);
	
	//Check for double Entries
	$query = mysqli_query(Connect_Database(),"SELECT `restaurant_check_no` AS check_no FROM `restaurant_table` WHERE `restaurant_check_no`= '$restaurant_check_no'");
	$double_entry = (mysqli_fetch_assoc($query));
	$double_entry_result =  $double_entry['check_no'];	
	
	if($double_entry_result){
		//Transaction exists in Restaurant Table
		$result = 1;
	}else{
	
		//If the restaurant_check_no doesn't Exist Insert Data to restaurant_table 
		$insert_into_restaurant_account ="INSERT INTO `restaurant_table` (`folio_number`,`gr_card_no`,`guest_name`,`restaurant_date`,`restaurant_check_no`,`restaurant_description`,`restaurant_amount`,`payment_type`) VALUES ('$folio','$gr_card','$guest_name','$restaurant_date','$restaurant_check_no','$restaurant_description','$restaurant_amount','$restaurant_pay_type');";

		if (mysqli_query(Connect_Database(), $insert_into_restaurant_account)) {
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
}
?>