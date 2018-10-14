<?php
//include the init file for all functions
include 'core/init.php';
$del_id = $_GET['del'];		
//if(empty($_POST)===false){
					
	//	$item_id = $_POST['item_id'];
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$create_date = date('Y-m-d H:i:s');
						
		$insert_into_rewards_details = "DELETE FROM `rewards_opt_tbl` WHERE `id` = '$del_id'";
		
		$zeal = mysqli_query(Connect_Database(), $insert_into_rewards_details)? 2:0 ;
				
		if($zeal === 2){
			//Posting success
			echo 2;
			header('Location:../../rewards/');
		}else{
			//posting failed
			echo 0;
		} 

//}
?>