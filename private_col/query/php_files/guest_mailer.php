<?php
include 'core/init.php';
//Verifys user logged in 

require '../../../vendors/composer_installed/autoload.php';

   if((isset($_POST['guest_mail'])===true && empty($_POST['guest_mail'])=== false) &&
	  (isset($_POST['guest_name'])===true && empty($_POST['guest_name'])=== false)){
				
		$sent_to = sanitize($_POST['guest_mail']);
		$register_name = sanitize($_POST['guest_name']);

		$invitor_id = $_SESSION['assignee']; 
		$invitor_query = "SELECT `username` as name FROM `privileged_users` WHERE `password` = '$invitor_id';";
		$invitor_result = mysqli_fetch_assoc(mysqli_query(Connect_Database(), $invitor_query));
		$invitor_name = $invitor_result['name'];
		
 		$mail_message_html; 
		$mail_message_txt;
		$message_subject;
		
			$mail_message_html = '<!doctype html>
								<html lang="en">
								<body>
								<h3 style="text-align:center;">Hello '.$register_name.'</h3>
								<br>
								<p style="text-align:center;"> Thank you for your interest to the <strong> Naivasha Kongoni Lodge</strong>.
								<br>We strive to make your stay here with us the best and give you an experience to remember.
								<br>Kindly complete your registration and create your account by following this link:
								<br>
								</p>
								<h4 style="text-align:center;"> <a href="http://localhost/INDULGENCE_STANDARD/private_col/dash/reg/guest_acc.php?srdtfyuijknbvcfdrtyuijkmnbvcfdrtyuhjknbvcgfdrtyuijknmbvcfdyuhjknbvgfdtyhj='.$register_asignee.'&uhiufurebuidcuyegvfuihsdhjkshfdssdjkcdjhjgvgvvgvtfyvyvvgzxfgxvcjhuhuhuhoo='.$profile_type.'"> Click Here to register. </a></h4>
								</body>
								</html>';
			$mail_message_txt = 'Welcome';
			$message_subject = 'Naivasha Kongoni Lodge Loyalty Program Guest Invitation.';
			
		//$query = mysqli_query(Connect_Database(),"SELECT `email` AS my_mail FROM `privileged_users` WHERE `email`= '$sent_to'");
		//$result = (mysqli_fetch_assoc($query));
		//$result =  $result['my_mail'];
		
		//if($result){
			//Member exists
		//	echo 'x';
		//}else{ 
			
			//echo $register_name;		
			refer_mail($sent_to,$mail_message_html,$mail_message_txt,$message_subject);
		//  }
		  
	}else{
		return 1;
		//echo $sent_to.' '.$mail_message_html.' '.$mail_message_txt.' '.$message_subject;
	}

	function refer_mail($sent_to,$mail_message_html,$mail_message_txt,$message_subject){

			// Create the Transport
			$transport = (new Swift_SmtpTransport('mail.indulgencemarketing.co.ke', 465, "ssl"))
			->setUsername('kongoniloyalty@indulgencemarketing.co.ke')
			->setPassword('1M&Nklai12018');
			;

			// Create the Mailer using your created Transport
			$mailer = new Swift_Mailer($transport);

			// Create a message
			$message = (new Swift_Message($message_subject))
			  ->setFrom(['kongoniloyalty@indulgencemarketing.co.ke' => 'Naivasha Kongoni Loyalty Program'])
			  ->setTo($sent_to)
			  
			// Give it a txt body
			  ->setBody($mail_message_txt)
			  
			// And optionally an html alternative body
			  ->addPart($mail_message_html, 'text/html');
			  ;

			// Send the message
			$result = $mailer->send($message,$failedRecipients);

				if($result){
						
						$result = 2;
					}
					
				echo $result;
				//add_to_logs($failedRecipients[0],$sent_to);

			}
	
	function add_to_logs($log_data,$receiver_mail){
		
			date_default_timezone_set('Africa/Nairobi');
			$log_date = date('Y-m-d H:i:s');
		
			//If the ID doesn't Exist Insert Data to member table 
			$insert_into_mail_error_logs = "INSERT INTO `mail_error_logs` (`log_date`, `log_data`, `receiver_mail`)
			VALUES ('$log_date', '$log_data', '$receiver_mail');";
	
								
		if (mysqli_query(Connect_Database(), $insert_into_mail_error_logs)) {
			//Success
			//Do Nothing
							
		} else {
			//Error Log file created
			$log_file = fopen('log_files/'.$log_date.'.txt', "w") or die("Unable to open file!");
			$txt =$log_data;
			fwrite($log_file, $txt);
			fclose($log_file);
		}
	}
?>