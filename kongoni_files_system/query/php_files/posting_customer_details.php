<?php
include 'core/init.php';

//Registration of new members into the DB.
 if((isset($_POST['registration_card_no'])===true && empty($_POST['registration_card_no']) === false)
	&&(isset($_POST['register_id_no'])===true && empty($_POST['register_id_no']) === false)){

	$registration_card_no = sanitize($_POST['registration_card_no']);
	$card_type = sanitize($_POST['card_type']);
	$group_identifier = sanitize($_POST['group_identifier']);
	$register_surname = sanitize($_POST['register_surname']);
	$register_first_name = sanitize($_POST['register_first_name']);
	$register_other_name = sanitize($_POST['register_other_name']);
	$register_phone_number = sanitize($_POST['register_phone_number']);
	$register_email_address = sanitize($_POST['register_email_address']);
	$register_city = sanitize($_POST['register_city']);
	$register_country = sanitize($_POST['register_country']);
	$register_dob = sanitize($_POST['register_dob']);
	$gender_type = sanitize($_POST['gender_type']);
	$register_id_no = sanitize($_POST['register_id_no']);
	$passport_expiry = sanitize($_POST['passport_expiry']);
	$register_nationality = sanitize($_POST['register_nationality']);
	$register_foreigner_certificate = sanitize($_POST['register_foreigner_certificate']);
	$register_arrival_date = sanitize($_POST['register_arrival_date']);
	$register_arrival_time = sanitize($_POST['register_arrival_time']);
	$register_arrival_from = sanitize($_POST['register_arrival_from']);
	$register_departure_date = sanitize($_POST['register_departure_date']);
	$register_departure_time = sanitize($_POST['register_departure_time']);
	$register_departure_destination = sanitize($_POST['register_departure_destination']);
	$register_nights_spent = sanitize($_POST['register_nights_spent']);
	$register_company_name = sanitize($_POST['register_company_name']);
	$register_profession = sanitize($_POST['register_profession']);
		
		
		//Check for double Entries
	$query = mysqli_query(Connect_Database(),"SELECT COUNT(`register_id_no`) AS register_id_no FROM `guests_contacts_data` WHERE `register_id_no`= '$register_id_no'");
	$double_entry = (mysqli_fetch_assoc($query));
	$double_entry_result =  $double_entry['register_id_no'];	
	
	if($double_entry_result > 0){
		//Transaction exists in Restaurant Table
		$result = 1;
	}else{
		
		//If the ID doesn't Exist Insert Data to member table 
		$insert_into_kongoni_account ="INSERT INTO `guests_contacts_data`(`registration_card_no`, `card_type`, `group_identifier`, `register_surname`, `register_first_name`, `register_other_name`, `register_phone_number`,`register_email_address`, `register_city`, `register_country`, `register_dob`, `gender_type`, `register_id_no`, `passport_expiry`, `register_nationality`,`register_foreigner_certificate`, `register_arrival_date`, `register_arrival_time`, `register_arrival_from`, `register_departure_date`, `register_departure_time`,`register_departure_destination`, `register_nights_spent`, `register_company_name`, `register_profession`) VALUES('$registration_card_no', '$card_type', '$group_identifier', '$register_surname', '$register_first_name', '$register_other_name', '$register_phone_number','$register_email_address', '$register_city', '$register_country', '$register_dob', '$gender_type', '$register_id_no', '$passport_expiry', '$register_nationality','$register_foreigner_certificate', '$register_arrival_date', '$register_arrival_time', '$register_arrival_from', '$register_departure_date', '$register_departure_time','$register_departure_destination', '$register_nights_spent', '$register_company_name', '$register_profession');";
									
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
	
	//echo $result;
	echo $insert_into_kongoni_account.'  '.$result;
}
?>