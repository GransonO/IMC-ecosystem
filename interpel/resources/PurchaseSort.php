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
	for ($x = 0; $x <= 367; $x++)
	{ 

   $arrayofrows = $row;
   $member_id = $arrayofrows[$x][5]; 
   
	//Queries latest transaction date
	$Date_query  = "SELECT SUM(`transaction_amount`) AS Sum_Amount FROM `member_transactions` WHERE `member_id` = '$member_id'";
	$Dquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Date_query));
	$Sum_Amount = $Dquery['Sum_Amount'];
	
	//echo $max_date;
	$update_me = "UPDATE `member_loyalty_funds` SET `total_purchase` = '$Sum_Amount' WHERE `member_id` = '$member_id';";
	$update_done = mysqli_query(Connect_Database(), $update_me);
	
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