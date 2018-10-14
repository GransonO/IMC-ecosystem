<?php
//include the init file for all functions
include 'core/init.php';
		
if(empty($_POST)===false){
					
		$item_name = $_POST['item_name'];
		$threshold_points = $_POST['threshold_points'];
		$expiry_date = $_POST['expiry_date']; 
		$creator_name = $_SESSION['assignee']; 
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$create_date = date('Y-m-d H:i:s');
						
		$insert_into_rewards_details = "INSERT INTO `rewards_opt_tbl`
		(`added_date`, `status`, `threshold`, `offer`, `redemptions_count`, `added_by`, `deactivated_on`, `deactivated_by`) VALUES 
		('$create_date', '1', '$threshold_points', '$item_name', '0', '$creator_name', '$expiry_date', 'NULL');";
		
		$zeal = mysqli_query(Connect_Database(), $insert_into_rewards_details)? 2:0 ;
				
		if($zeal === 2){
			//Posting success
			echo 2;
			
		}else{
			//posting failed
			echo 0;
		} 

}
?>