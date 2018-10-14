<?php
include 'core/init.php';

//Registration of new members into the DB.
 if((isset($_POST['folio'])===true && empty($_POST['folio']) === false)
	&&(isset($_POST['total_bill'])===true && empty($_POST['total_bill']) === false)){

	$folio = sanitize($_POST['folio']);
	$gr_card = sanitize($_POST['gr_card']);
	$guest_name = sanitize($_POST['guest_name']);
	$guest_type = sanitize($_POST['guest_type']);
	$phone_no = sanitize($_POST['phone_no']);
	$email_contact = sanitize($_POST['email_contact']);
	$nationality = sanitize($_POST['nationality']);
	$booking_source = sanitize($_POST['booking_source']);
	$room_number = sanitize($_POST['room_number']);
	$room_type = sanitize($_POST['room_type']);
	$tariff = sanitize($_POST['tariff']);
	$persons = sanitize($_POST['persons']);
	$arrival_date = sanitize($_POST['arrival_date']);
	$arrival_time = sanitize($_POST['arrival_time']);
	$departure_date = sanitize($_POST['departure_date']);
	$nights_spent = sanitize($_POST['nights_spent']);
	$guest_pay = sanitize($_POST['guest_pay']);
	$ots_bill = sanitize($_POST['ots_bill']);
	$total_bill = sanitize($_POST['total_bill']);
		
		
		//Check for double Entries
	$query = mysqli_query(Connect_Database(),"SELECT `folio_number` AS folio_number FROM `guests_data` WHERE `folio_number`= '$folio'");
	$double_entry = (mysqli_fetch_assoc($query));
	$double_entry_result =  $double_entry['check_no'];	
	
	if($double_entry_result){
		//Transaction exists in Restaurant Table
		$result = 1;
	}else{
		
		//If the ID doesn't Exist Insert Data to member table 
		$insert_into_kongoni_account ="INSERT INTO `guests_data`
		(`folio_number`,`gr_card_no`,`guest_name`,`guest_type`,`phone_no`,`email_address`,`nationality`, `source`,`room_no`,`room_type`,`tariff`,`no_of_persons`,`arrival_date`,`arrival_time`,`departure_date`,`nights_spent`,`guest_payement`,`bill_to_ots`,`final_total`)VALUES('$folio','$gr_card','$guest_name','$guest_type','$phone_no','$email_contact','$nationality','$booking_source','$room_number','$room_type','$tariff','$persons','$arrival_date','$arrival_time','$departure_date','$nights_spent','$guest_pay','$ots_bill','$total_bill');";
		
									
			if (mysqli_query(Connect_Database(), $insert_into_kongoni_account)) {
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
	//echo $insert_into_user_accounts.'  '.$result;
}
?>