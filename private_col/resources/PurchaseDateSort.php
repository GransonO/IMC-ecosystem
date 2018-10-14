<?php 
//Get top Five Values
include 'init.php';


//Queries total transaction count
$Recent_Points  = "SELECT COUNT(`member_id`) AS id_count FROM `member_loyalty_funds`";
$Dquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Recent_Points));
$recent_data_count = $Dquery['id_count'];
	
$total_count = $recent_data_count - 1;

$query = "SELECT *
          FROM `member_loyalty_funds`";
$result = mysqli_query(Connect_Database(),$query);
$arrayofrows = array();
while($row = mysqli_fetch_all($result))
{
	
	for ($x = 0; $x <= $total_count; $x++)
	{ 

   $arrayofrows = $row;
   $member_id = $arrayofrows[$x][5];
   
	//Queries latest transaction date
	$Date_query  = "SELECT MAX(`transaction_date`) AS trans_date FROM `member_transactions` WHERE `member_id` = '$member_id'";
	$Dquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Date_query));
	$trans_date = $Dquery['trans_date'];
	
	//echo $max_date;
	$update_me = "UPDATE `member_loyalty_funds` SET `max_trans_date` = '$trans_date' WHERE `member_id` = '$member_id';";
	$update_done = mysqli_query(Connect_Database(), $update_me);
	
	echo 'Member ID: '.$member_id.'</br>Transaction Date: '.$trans_date.'</br>Update Date: '.$update_me.'</br></br>';
	} 
}
//Active Preformers based on Redemptions And Purchase

?>
<!DOCTYPE html>
<html>

<head>
<title>Tests Stuff</title>
</head>
	
<body>
<p><?php echo 'Done Processing '.$x.' Where the final member ID is :'.$member_id;?></p>
</body>
</html>