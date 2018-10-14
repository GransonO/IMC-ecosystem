<?php
include 'init.php';
//require '/var/www/html/interpel/interpelrewards/backend/mailing/vendor/swiftmailer/swiftmailer/lib/swift_required.php';

//if(isset($_POST['expiry_check'])){
//Gets the date time of redemption
		$date = new DateTime();//now 
		$interval = new DateInterval('P2M');// P[eriod] 1 M[onth]
		$yearval = new DateInterval('P1Y');// P[eriod] 1 Y[ear]
		$date->sub($yearval);
		$date->add($interval);
		$expire_date = $date->format('Y-m-d'); 
		
		date_default_timezone_set('Africa/Nairobi');
		$year = date('Y');
		$month = date('m');
		$day = date('d');
		
		$current_year = $year - 1;
		$current_date =  $current_year.'-'. $month .'-'. $day;
		
		$message_now = '		
		<!DOCTYPE html>
		<html>
		<body>
		<h2 style="color:#000;"><strong>Hello Makena & Granson,</strong></h2> 

		These are the points scheduled to expire in the nest two months.
		All are in arrays and in order.
		
		<p><strong>Order of entries </br>Member Id, Member Name, Available Points, Maximum Transaction Date </p>
		
					<div class="" style="overflow: auto; height:200px">
						<table class="table table-hover" >
						  <thead>
							<tr>
							  <th>#</th>
							  <th style="color:black;">Member_Id</th>
							  <th style="color:black;">Member_Name</th>
							  <th style="color:black;">  Available Points</th>
							  <th style="color:black;"> Maximum Transaction Date</th>
							</tr>
						  </thead>
						  <tbody>'.
									//Queries customer table values
									$x = 0;
									$Table_query2  = "SELECT `member_id`,`company_name`,`total_points_available`,`max_trans_date` FROM `member_loyalty_funds` WHERE `max_trans_date` BETWEEN '$current_date' AND '$expire_date'";
									$tablequery2 = mysqli_query(Connect_Database(),$Table_query2);
									while($table_data2 = mysqli_fetch_assoc($tablequery2)){
										$x = $x + 1;
										echo "<tr>";
										echo "<th scope=\"row\">" .$x. "</th>";
										echo '<td style="color:red;">' .$table_data2['member_id'] . '</td>' ;
										echo '<td style="color:red;">' .$table_data2['company_name'] . '</td>' ;
										echo '<td style="color:red;">' .$table_data2['total_points_available'] . '</td>' ;
										echo '<td style="color:red;">' .$table_data2['max_trans_date'] . '</td>' ;
										echo "</tr>";  
									}'.
						  </tbody>
						</table>
					  </div></br>
		Please contact them and inform them of this.

		<p style="color:#000;font-size:13px;" >regards (indulgence Relay system)</p>

			<footer>
			<p style="color:#000;font-size:13px;" >This mail will resend weekly</p>
			<img src="http://178.62.100.247/interpel/interpelrewards/production/images/logo.png" alt="Indulgence" height="100" width="300">
			</footer>
		</body>
		</html>		
		';
//echo $message_now;
//		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
//		->setUsername('indulgenceit.development@gmail.com')
//		->setPassword('T1tiITp2*');

//		$mailer = Swift_Mailer::newInstance($transport);

//		$message = Swift_Message::newInstance('Points expiry schedule')
//		->setFrom(array('info@indulgencemarketing.co.ke' => 'Indulgence System'))
//		->setTo(array('makena@indulgencemarketing.co.ke' => 'Makena','granson@indulgencemarketing.co.ke' => 'Granson','corumdeveloper@gmail.com' => 'Corum'))
//		->setContentType("text/html")
//		->setBody($message_now,'text/html');

//		$result = $mailer->send($message);

//		if ($result) {
//			  echo "Email sent successfully";
//			$status = '?kjkfwjhoufhuwei677870423u4ouheb72379uhruegf=23152huiunjknhjf625635';//Process done successfully
//			}
//			else
//			{
//			  echo "Email failed to send";
//			$status = '?kjkfwjhoufhuwei677870423u4ouheb72379uhruegf=23152njknhjf694895jjj25635';//Process failed
//			}
					//redirect to the listing page
//		header("Location: Data_Uploader.php".$status);
//	}
	?>

<!DOCTYPE html>
<html>
<head>
<title>Expire Stuff</title>
</head>
	
<body>

<?php
echo $message_now;
?>

</body>
</html>