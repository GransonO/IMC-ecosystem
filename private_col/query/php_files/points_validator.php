<?php
require 'core/init.php'; 
//get points for specific member
//Compare points with table of available rewards
//return list if all items that surpass 
if(empty($_POST) == false){
	$member_id = $_POST['reward_id'];
	$get_data = "SELECT * FROM `member_loyalty_funds` WHERE `member_id` = '$member_id'";
	$data_results = mysqli_fetch_assoc(mysqli_query(Connect_Database(),$get_data));
	$member_name = $data_results['company_name'];
	$member_points = $data_results['total_points_available'];
	if($member_name){
	
			$get_rewards = "SELECT `offer` as OFFERZ FROM `rewards_opt_tbl` WHERE `threshold` < '$member_points' AND `status` = '1'";
			$rewards_query = mysqli_query(Connect_Database(),$get_rewards);
			$rewards_array = array();
			while($returned_offers = mysqli_fetch_assoc($rewards_query)){
			if($returned_offers['OFFERZ']){
				
				array_push($rewards_array,$returned_offers['OFFERZ']);
				}else{
				
				array_push($rewards_array,"Doesn't qualify yet...");
					}
				};
			$myObj = new \stdClass();
			
			$myObj->name = $member_name;
			$myObj->my_status = $member_points;
			$myObj->rewards = $rewards_array;

			$myJSON = json_encode($myObj);

			echo $myJSON;		
		}else{
		
			echo '0';
		}

}
?>