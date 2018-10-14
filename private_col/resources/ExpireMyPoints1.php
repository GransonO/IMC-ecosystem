<?php 
//Get top Five Values
include 'init.php';

if (empty($_POST['input_date']) === false){
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$current_date = date('Y/m/d');
			
		$input_date = $_POST['input_date'];
		$query = "SELECT * FROM `member_loyalty_funds` WHERE `max_trans_date` < '$input_date' ";
		$result = mysqli_query(Connect_Database(),$query);
		$arrayofrows = array();
		while($row = mysqli_fetch_all($result))
		{
			
			for ($x = 0; $x <= 5; $x++)
			{ 

		   $arrayofrows = $row;
		   $member_id = $arrayofrows[$x][5];
		   
			//Queries latest transaction date
			$Date_query  = "SELECT `total_points_available` AS avail_points,`expired_loyalty` AS expire_points FROM `member_loyalty_funds` WHERE `member_id` = '$member_id'";
			$Dquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Date_query));
			$avail_points = $Dquery['avail_points'];
			$expire_points = $Dquery['expire_points'];
			//echo $max_date;
			if($avail_points > 0){
			echo 'MEMBER ID: '.$member_id.'</br> Points expired: '.$avail_points;
			
			$expired = $avail_points + $expire_points;
			
			$update_me = "UPDATE `member_loyalty_funds` SET `total_points_available` = '0',`expired_loyalty`='$expired' WHERE `member_id` = '$member_id'";
			//$update_done = mysqli_query(Connect_Database(), $update_me);
			
			$insert_to_expired = "INSERT INTO `Points_Expiry` (`member_id`, `expired_points`, `expiry_date`, `expired_by`, `reason_expired`) VALUES ('$member_id', '$avail_points', '$current_date', 'Automate', 'over due')";
			//$insert_done = mysqli_query(Connect_Database(), $insert_to_expired);
			
			//if($insert_done){
						echo '</br>Inserted :'.$member_id.' to the expiry table</br>';
			//		}
				}
		} 
	}
}
//Active Preformers based on Redemptions And Purchase

?>
<!DOCTYPE html>
<html>
<head>
<title>Expire Stuff</title>
</head>
	
<body>
  <form action="" method="post"> 
	<div class="form-group">
		<div class="col-md-12 col-sm-12 col-xs-12 "  style="text-align: center;">
			<input type="date" class="form-control" name="input_date"	placeholder="Expiry date" required="required"/>
			<button type="submit" name="submitted" value="DONE" class="btn btn-success">Approve</button>
	  </div>
	  </div>
	  </form>
<p><?php echo 'Done Expiring '.$x.' Where the final member ID is :'.$member_id;?></p>
</body>
</html>`