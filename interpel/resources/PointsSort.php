<?php 
//Get top Five Values
include 'init.php';
$bid = 0;

$query = "SELECT *
          FROM `interpel_point_grouping`;";
$result = mysqli_query(Connect_Database(),$query);
$arrayofrows = array();
while($row = mysqli_fetch_all($result))
{
	for ($x = 0; $x <= 368; $x++)
	{ 
   $arrayofrows = $row;
   $member_id = $arrayofrows[$x][1];
   
	//Queries latest transaction date
	$Date_query  = "SELECT SUM(`transaction_amount`) AS max_amount, SUM(`points_earned`) AS max_points, COUNT(`points_earned`) AS trans_count FROM `recent_data` WHERE `member_id` = '$member_id'";
	$Dquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Date_query));
	$max_amount = $Dquery['max_amount'];
	$max_points = $Dquery['max_points'];
	$trans_count = $Dquery['trans_count'];
	
	//echo $max_date;
	if($max_amount == NULL){
		$update_me = "UPDATE `interpel_point_grouping` SET `total_purchase` = '0',`total_points` = '0',`transaction_count` = '0' WHERE `member_id` = '$member_id'";
		$update_done = mysqli_query(Connect_Database(), $update_me);
		//echo $update_me.'</br>';
	} else{
		$update_me = "UPDATE `interpel_point_grouping` SET `total_purchase` = '$max_amount',`total_points` = '$max_points',`transaction_count` = '$trans_count' WHERE `member_id` = '$member_id'";
		$update_done = mysqli_query(Connect_Database(), $update_me);
		//echo $update_me.'</br>';
		}
//Active Preformers based on Redemptions And Purchase
			}
		}
?>
<!DOCTYPE html>
<html>
<head>
<title>Tests Stuff</title>
</head>
	
<body>
<p><?php echo 'Process complete Last mem_id= '.$member_id;?></p>
</body>
</html>