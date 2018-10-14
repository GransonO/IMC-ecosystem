<?php
include 'core/init.php';
//Verifys user logged in 

require '../../../vendors/composer_installed/autoload.php';

   if((isset($_POST['recipient_mail'])===true && empty($_POST['recipient_mail'])=== false) &&
	  (isset($_POST['message_type'])===true && empty($_POST['message_type'])=== false) &&
	  (isset($_POST['profile_type'])===true && empty($_POST['profile_type'])=== false) &&
	  (isset($_POST['register_asignee'])===true && empty($_POST['register_asignee'])=== false) &&
	  (isset($_POST['register_name'])===true && empty($_POST['register_name'])=== false)){
				
		$sent_to = sanitize($_POST['recipient_mail']);
		$message_type = sanitize($_POST['message_type']);
		$register_name = sanitize($_POST['register_name']);
		$profile_type = sanitize($_POST['profile_type']);
		$register_asignee = sanitize($_POST['register_asignee']);
		
 		$mail_message_html; 
		$mail_message_txt;
		$message_subject;
		
		if($message_type == 1){//Registration 
			$mail_message_html = '<!doctype html>
								<html lang="en">
								<body>
								<h3 style="text-align:center;">Hello '.$register_name.'</h3>
								<br>
								<p style="text-align:center;"> You have been invited to administrate the <strong>Interpel CFS Loyalty Program</strong> under the <strong>'.$profile_type.'</strong> profile.
								<br>Kindly complete your registration and create your account by following this link:
								<br>
								</p>
								<h4 style="text-align:center;"> <a href="http://localhost/INDULGENCE_STANDARD/new_interpel/dash/user_registration.php?srdtfyuijknbvcfdrtyuijkmnbvcfdrtyuhjknbvcgfdrtyuijknmbvcfdyuhjknbvgfdtyhj='.$register_asignee.'&uhiufurebuidcuyegvfuihsdhjkshfdssdjkcdjhjgvgvvgvtfyvyvvgzxfgxvcjhuhuhuhoo='.$profile_type.'"> Click Here to register. </a></h4>
								</body>
								</html>';
			$mail_message_txt = 'Welcome';
			$message_subject = 'Interpel CFS Loyalty Program Admin Invitation.';
		}
	
		$query = mysqli_query(Connect_Database(),"SELECT `email` AS my_mail FROM `privileged_users` WHERE `email`= '$sent_to'");
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
			$transport = (new Swift_SmtpTransport('mail.indulgencemarketing.co.ke', 465, "ssl"))
			->setUsername('interpelrewards@indulgencemarketing.co.ke')
			->setPassword('1M&Nklai12018');
			;

			// Create the Mailer using your created Transport
			$mailer = new Swift_Mailer($transport);

			// Create a message
			$message = (new Swift_Message($message_subject))
			  ->setFrom(['interpelrewards@indulgencemarketing.co.ke' => 'Interpel CFS Loyalty System'])
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