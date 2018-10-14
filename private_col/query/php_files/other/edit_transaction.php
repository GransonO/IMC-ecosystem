<?php
include 'core/init.php';
//Verifys user logged in 

$USER_ID = $_SESSION['ID'];
	if($USER_ID === null){
		//Show loggin error
	header('Location: login_error.php');
	
	}else{
		//do nothing
	}

		//Get User Details
	$User_Data = "SELECT `username` AS Name FROM privileged_users WHERE `password` = '$USER_ID'"; 
	$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
	$username = $U_query['Name'];
	
//Registration of new members into the DB.
if((isset($_POST['phone_no'])===true && empty($_POST['phone_no']) === false)&&(isset($_POST['trans_receipt'])===true && empty($_POST['trans_receipt']) === false)&&(isset($_POST['trans_paid'])===true && empty($_POST['trans_paid']) === false)&&(isset($_POST['trans_price'])===true && empty($_POST['trans_price']) === false)){
		
		$member_id = $_POST['phone_no'];
		$trans_type_category = $_POST['trans_type_category'];
		$trans_sales_category = $_POST['trans_sales_category'];
		$trans_describe = $_POST['trans_describe'];
		$trans_sales_payement = $_POST['trans_sales_payement'];
		$price = $_POST['trans_price'];
		$paid = $_POST['trans_paid'];
		$receipt = $_POST['trans_receipt'];
		
		
	$G_ID = "SELECT `member_id` AS ID FROM member WHERE `member_id` = '$member_id'"; 
	$ID_G =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$G_ID));
	$Gresult = ($ID_G['ID'])? $ID_G['ID']: 0;
	
	if($Gresult === 0){
		//If the ID DOES NOT exist
		$result = 0;
		echo $result;
		}
		else{
				$points = ($paid*0.02);
				$type = $trans_type_category.' '.$trans_sales_category;
				$balance = $price - $paid;
				
				date_default_timezone_set('Africa/Nairobi');
				$Trans_date = date('Y-m-d H:i:s');	
				$month = date('m');	
				$year = date('Y');
										
					$loyalty_Data = "SELECT * FROM member_loyalty_funds WHERE `member_id` = '$member_id'"; 
					$L_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$loyalty_Data));
					$total_points_earned = $L_query['loyalty_value_earned'];
					$total_points_available = $L_query['total_points_available'];
					$member_name = $L_query['member_name'];
					
					$Add_to_total_points = $total_points_earned + $points;
					$Add_to_available_points = $total_points_available + $points;
					
					$update_loyalty = "UPDATE `member_loyalty_funds` SET  `total_points_available` = '$Add_to_available_points', `loyalty_value_earned` = '$Add_to_total_points',`max_trans_date` = '$Trans_date' WHERE `member_id` = '$member_id ';";
					
					$insert_trans = "INSERT INTO `member_transactions` (
					`uploaded_by`, `is_deleted`, `member_id`, `member_name`, `transaction_code`, `category_type`, `description`, `payement_option`, `amount_due`,`transaction_date`,`amount_paid`,`balance`, `loyalty_earned`, `month`, `year`, `date_deleted`, `deleted_by`, `reason_deleted`)
					VALUES (
					'$username', '0', '$member_id', '$member_name', '$receipt','$type','$trans_describe','$trans_sales_payement', '$price', '$Trans_date', '$paid', '$balance', '$points', '$month', '$year', NULL, 'TT', 'TT');";
					
				//	echo $insert_trans;
					$zeal = mysqli_query(Connect_Database(), $insert_trans)? 1:0 ;
					
					if($zeal){
					$real = mysqli_query(Connect_Database(), $update_loyalty)? 1:0 ;
					}else{
						$real = 0;
					}
					
					if($real == 1 && $zeal == 1){
					//Posting successfull
					$result = 1;
					echo $result;
					 
					}else
						{
					//Posting unsuccessfull
					$result = 2;
					echo $result;
					
					}
				}
			}
?>