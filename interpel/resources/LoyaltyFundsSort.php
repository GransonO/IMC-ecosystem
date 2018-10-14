<?php 
//Get top Five Values
include 'init.php';
$bid = 0;

$query = "SELECT *
          FROM `member_loyalty_funds`";
$result = mysqli_query(Connect_Database(),$query);
$arrayofrows = array();
while($row = mysqli_fetch_all($result))
	
{
	for ($x = 0; $x <= 369; $x++ )
	{ 
   $arrayofrows = $row;
   $member_id = $arrayofrows[$x][5];
   $loyalty_earned = $arrayofrows[$x][7];
   $points_available = $arrayofrows[$x][8];
   
	//Queries latest transaction date
	$Date_query  = "SELECT `total_purchase` AS max_amount, `total_points` AS max_points FROM `interpel_point_grouping` WHERE `member_id` = '$member_id'";
	$Dquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Date_query));
	$max_amount = $Dquery['max_amount'];
	$max_points = $Dquery['max_points'];
	
		$earned_loyalty = $loyalty_earned + $max_points;
		$availably_loyalty = $points_available + $max_points;
	
		//echo $max_date;
		$update_me = "UPDATE `member_loyalty_funds` SET `loyalty_value_earned` = '$earned_loyalty',`total_points_available` = '$availably_loyalty' WHERE `member_id` = '$member_id'";
		echo $points_available.' --- '.$max_points.'</br>';
		echo $loyalty_earned.' --- '.$max_points.'</br>'.$update_me.'</br> </br>';
		$update_done = mysqli_query(Connect_Database(), $update_me);
		echo $update_done.'</br>';
		//Active Preformers based on Redemptions And Purchase
			}
		}
?>
<!DOCTYPE html>
<html>
<head>
<title>Adding Loyalty To The Main Loyalty Funds TABLE</title>
</head>
	
<body>
<p><?php echo 'Process complete for the Loyalty Funds TABLE Addition .Last ID is: '.$member_id;?></p>
</body>
</html>