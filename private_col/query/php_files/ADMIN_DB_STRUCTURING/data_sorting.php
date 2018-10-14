<?php
require 'core/init.php';

$get_all_entries = 'SELECT * FROM `member_loyalty_funds`';
$query_result = mysqli_query(Connect_Database(), $get_all_entries);

while($query = mysqli_fetch_assoc($query_result)){
	$the_Id = $query['member_id'];
	$delete_if_present = "DELETE FROM `all_customers_file` WHERE `mem_id` = '$the_Id'";
	$deleting = mysqli_query(Connect_Database(), $delete_if_present);
	echo 'Deleted '. $the_Id.' :  '.$deleting. '<br>'; 
}
	
?>