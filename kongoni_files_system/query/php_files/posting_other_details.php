<?php
include 'core/init.php';


 if((isset($_POST['folio'])===true && empty($_POST['folio']) === false)
	&&(isset($_POST['gr_card'])===true && empty($_POST['gr_card']) === false)
	&&(isset($_POST['service_date'])===true && empty($_POST['service_date']) === false)
	&&(isset($_POST['service_check_no'])===true && empty($_POST['service_check_no']) === false)
	&&(isset($_POST['service_amount'])===true && empty($_POST['service_amount']) === false)){

	$folio = sanitize($_POST['folio']);
	$gr_card = sanitize($_POST['gr_card']);
	$guest_name = sanitize($_POST['guest_name']);
	$service_date = sanitize($_POST['service_date']);
	$service_check_no = sanitize($_POST['service_check_no']);
	$service_type = sanitize($_POST['service_type']);
	$service_amount = sanitize($_POST['service_amount']);
	$service_pay_type = sanitize($_POST['service_pay_type']);
	
	//Check for double Entries
	$query = mysqli_query(Connect_Database(),"SELECT `receipt_check_no` AS check_no FROM `others_table` WHERE `receipt_check_no`= '$service_check_no'");
	$double_entry = (mysqli_fetch_assoc($query));
	$double_entry_result =  $double_entry['check_no'];	
	
	if($double_entry_result){
		//Transaction exists in Others Table
		$result = 1;
	}else{
	
		//If the service_check_no doesn't Exist Insert Data to others_table 
		$insert_into_restaurant_account ="INSERT INTO `others_table` 
		(`folio_number`,`gr_card_no`,`guest_name`,`receipt_date`,`receipt_check_no`,`receipt_description`,`receipt_amount`,`payment_type`)
		VALUES 
		('$folio','$gr_card','$guest_name','$service_date','$service_check_no','$service_type','$service_amount','$service_pay_type');";

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