<?php
include 'init.php';
require __DIR__ . '/mailing/vendor/swiftmailer/swiftmailer/lib/swift_required.php';

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
		
		$id_array = array();
		
		$query = "SELECT `member_id`,`company_name`,`total_points_available`,`max_trans_date` FROM `member_loyalty_funds` WHERE `max_trans_date` BETWEEN '$current_date' AND '$expire_date'";
		echo $query;
		$result = mysqli_query(Connect_Database(),$query);
			while($data = mysqli_fetch_assoc($result)){
				array_push($id_array,$data['member_id'].' || '.$data['company_name'].' || '.$data['total_points_available'].' || '.$data['max_trans_date'].' </br></br> ');
			}

		$string = implode('||                    ||',$id_array);
		$message_now = '<html>
				<body>
				<h2 style="color:#000;"><strong>Hello Makena & Granson,</strong></h2> 

				These are the points scheduled to expire in the nest two months.
				All are in arrays and in order.
				
				<p><strong>Order of entries </br>Member Id, Member Name, Available Points, Maximum Transaction Date </p></br>
				</br><h3><strong>'.$string.'</h3></br>

				Please contact them and inform them of this.

				<p style="color:#000;font-size:13px;" >regards (indulgence Relay system)</p>

					<footer>
					<p style="color:#000;font-size:13px;" >This mail will resend weekly</p>
					<img src="http://178.62.100.247/interpel/interpelrewards/production/images/logo.png" alt="Indulgence" height="100" width="300">
					</footer>
				</body>
			</html>';
//echo $message_now;
		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
		->setUsername('indulgenceit.development@gmail.com')
		->setPassword('254@Indulgence2017356282939###');

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance('Points expiry schedule')
		->setFrom(array('info@indulgencemarketing.co.ke' => 'Indulgence System'))
		->setTo(array('makena@indulgencemarketing.co.ke' => 'Makena','granson@indulgencemarketing.co.ke' => 'Granson','corumdeveloper@gmail.com' => 'Corum'))
		->setContentType("text/html")
		->setBody($message_now,'text/html');

		$result = $mailer->send($message);

		if ($result) {
			  echo "Email sent successfully";
			}
			else
			{
			  echo "Email failed to send";
			}
?>