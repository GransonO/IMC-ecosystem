<?php 
//Get top Five Values
include 'init.php';

if (isset($_POST['submitted'])){
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$current_date = date('Y/m/d');
		$input_date = $_POST['exp_date'];
			
		//Queries total transaction count
		$Recent_Points  = "SELECT COUNT(`member_id`) AS id_count FROM `member_loyalty_funds` WHERE `max_trans_date` < '$input_date' AND `total_points_available` > '0'";
		$Dquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Recent_Points));
		$recent_data_count = $Dquery['id_count'];
			
		$total_count = $recent_data_count;	
			
		$query = "SELECT * FROM `member_loyalty_funds` WHERE `max_trans_date` < '$input_date' AND `total_points_available` > '0'";
		$result = mysqli_query(Connect_Database(),$query);
		$arrayofrows = array();
		while($row = mysqli_fetch_all($result))
		{
			
			for ($x = 0; $x <= $total_count; $x++)
			{ 

		   $arrayofrows = $row;
		   $member_id = $arrayofrows[$x][5];
		   
			//Queries latest transaction date
			$Date_query  = "SELECT `total_points_available` AS avail_points,`expired_loyalty` AS expire_points FROM `member_loyalty_funds` WHERE `member_id` = '$member_id'";
			$Dquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Date_query));
			$avail_points = $Dquery['avail_points'];
			$expire_points = $Dquery['expire_points'];
			echo $member_id.' - '.$avail_points.' - '.$expire_points.'</br>';	
			
			if($avail_points > 0){
			echo $member_id.' - '.$avail_points.' - '.$expire_points.'</br>';			
			$expired = $avail_points + $expire_points;
			
			$update_me = "UPDATE `member_loyalty_funds` SET `total_points_available` = '0',`expired_loyalty`='$expired' WHERE `member_id` = '$member_id'";
			//$update_done = mysqli_query(Connect_Database(), $update_me);
			
			$insert_to_expired = "INSERT INTO `points_expiry` (`member_id`, `expired_points`, `expiry_date`, `expired_by`, `reason_expired`) VALUES ('$member_id', '$avail_points', '$current_date', 'Automate', 'over due')";
			//$insert_done = mysqli_query(Connect_Database(), $insert_to_expired);
			
			if($insert_done){
						echo '</br>Inserted :'.$member_id.' to the expiry table</br>';
					}
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
	  <div class="col-md-12 col-sm-12 col-xs-12 " style="overflow: auto; height: 200px">
		<table class="table table-hover">
		  <thead>
			<tr>
			  <th>#</th>
			  <th style="color:black;">Date</th>
			  <th style="color:black;">ID</th>
			  <th style="color:black;">Name</th>
			  <th style="color:black;">points</th>
			</tr>
		  </thead>
		  <tbody>
		  
		<?php
		//Queries customer table values
		if (isset($_POST['show_exipry'])){
			
		//Queries total transaction count
		$x = 0;	
		$input_date = $_POST['input_date'];
		$query = "SELECT * FROM `member_loyalty_funds` WHERE `max_trans_date` < '$input_date' AND `total_points_available` > '0' ";
		$result = mysqli_query(Connect_Database(),$query);
		$arrayofrows = array();
		while($row = mysqli_fetch_assoc($result))
		{
			
			$x = $x + 1;
			echo "<tr>";
			echo "<th scope=\"row\">" .$x. "</th>" ; ; 		
			echo "<td>" .$row['max_trans_date'] . "</td>" ; 
			echo "<td>" .$row['member_id'] . "</td>" ;  		
			echo "<td>" .$row['company_name'] . "</td>" ;
			echo "<td>" .$row['total_points_available'] . "</td>" ;
			echo "</tr>";  
			}
		}
		?>

		  </tbody>
		</table>
	  </div>
	<form action="" method="post"> 
		<div class="form-group">
		<div class="col-md-12 col-sm-12 col-xs-12 "  style="text-align: center;">
			<input type="date" class="form-control" name="input_date"	placeholder="Expiry date" required="required"/>
			<button type="submit" name="show_exipry" value="show_exipry" class="btn btn-success">Show Exipry</button>
	  </div>
	  </div>
	  </form>

	  <form method=""> 
		<div class="form-group">
		<div class="col-md-12 col-sm-12 col-xs-12 "  style="text-align: center;">
			<input type="date" class="form-control" name="exp_date"	placeholder="Expiry date" required="required"/>
			<button type="submit" name="submitted" value="DONE" class="btn btn-success">Expire</button>
	  </div>
	  </div>
	  </form>
<p><?php echo 'Done Expiring '.$x.' Where the final member ID is :'.$member_id;?></p>
</body>
</html>