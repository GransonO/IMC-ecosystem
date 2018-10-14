<?php
require 'core/init.php';

$get_all_entries = 'SELECT * FROM `member_loyalty_funds`';
$query_result = mysqli_query(Connect_Database(), $get_all_entries);

while($query = mysqli_fetch_assoc($query_result)){
	$the_Id = $query['member_id'];
	
	$get_sum = "SELECT SUM(`loyalty_earned`) as loyalty,sum(`transaction_amount`) as amount from `member_transactions` where `member_id` = '$the_Id'";
	$sum_result = mysqli_fetch_assoc(mysqli_query(Connect_Database(), $get_sum));
	$result = $sum_result['loyalty'];
	$amount = $sum_result['amount'];
	
	$points_updater = "UPDATE `member_loyalty_funds` SET `loyalty_value_earned` = '$result',`total_points_available` = '$result',`total_purchase` = '$amount' WHERE `member_id` = '$the_Id'";
	if(mysqli_query(Connect_Database(), $points_updater)){
		
			echo '<h5 style=color:green;>SUCCESS!!! For '. $the_Id.' the points are '.$result.' and amount is: '.$amount.'</h5><br><br>'; 
		}else{
			
			echo '<h6 style=color:red;>FAILED! For '. $the_Id.' the points are '.$result.' and amount is: '.$amount.'</h6><br><br>'; 
		}
	
}
?>