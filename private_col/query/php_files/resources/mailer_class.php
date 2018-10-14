<?php
include 'core/init.php';
//Verifys user logged in 

require '../../vendors/vendor/autoload.php';

   if((isset($_POST['recipient_mail'])===true && empty($_POST['recipient_mail'])=== false) &&
	  (isset($_POST['message_type'])===true && empty($_POST['message_type'])=== false) &&
	  (isset($_POST['register_name'])===true && empty($_POST['register_name'])=== false)){
		
		$sent_to = sanitize($_POST['recipient_mail']);
		$message_type = sanitize($_POST['message_type']);
		$register_name = sanitize($_POST['register_name']);
		
 		$mail_message_html; 
		$mail_message_txt;
		$message_subject;
		
		if($message_type == 1){//Registration 
			$mail_message_html = '<!doctype html>
								<html lang="en">
								<body>
								<h3 style="text-align:center;">Hello '.$register_name.'</h3>
								<br>
								<p style="text-align:center;">Thank you for registering with us. We welcome you to our movement and we are delighted to 
								work together and achieve our goals in making great future leaders from our students.
								<br>Kindly complete your registration by following this link:
								<br>
								</p>
								<h4 style="text-align:center;"> <a href="http://188.166.154.154/africa/apeiron/reg_io/reg_profile.html">Click Here to register.</a></h4>
								</body>
								</html>';
			$mail_message_txt = 'Welcome';
			$message_subject = 'Registration Time';
		}
	
		$query = mysqli_query(Connect_Database(),"SELECT `email_address` AS my_mail FROM `users_profile` WHERE `email_address`= '$sent_to'");
		$result = (mysqli_fetch_assoc($query));
		$result =  $result['my_mail'];
		
		if($result){
			//Member exists
			echo 'x';
		}else{
			
			//echo $register_name;		
			refer_mail($sent_to,$mail_message_html,$mail_message_txt,$message_subject);
		  }
		  
	}else{
		return 1;
		//echo $sent_to.' '.$mail_message_html.' '.$mail_message_txt.' '.$message_subject;
	}

	function refer_mail($sent_to,$mail_message_html,$mail_message_txt,$message_subject){

			// Create the Transport
			$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, "ssl"))
			->setUsername('africaapeiron@gmail.com')
			->setPassword('Power3942*');
			;

			// Create the Mailer using your created Transport
			$mailer = new Swift_Mailer($transport);

			// Create a message
			$message = (new Swift_Message($message_subject))
			  ->setFrom(['africaapeiron@gmail.com' => 'Asco E. Learning App'])
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