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
			$mail_message_html = '
			
				<html lang="en">
						<body>
							<!--   Big container   -->
							<div class="container">
								<div class="row">
								<div class="col-sm-8 col-sm-offset-2">

									<!--      Wizard container        -->
									<div class="wizard-container">
										<div class="card wizard-card" data-color="red" id="wizard">
										<form action="" method="">
									   
												<div class="wizard-header">
													<h3>
														<div style="text-align:center;">
															<img src="https://indulgecard.co.ke/admin/API/JADE_API/jade.png"  height="100" width="200">
														</div>
													</h3>
												</div>

												<div class="tab-content">
																			
												<div class="tab-pane" id="welcome">
													<div class="row">
														<div class="col-sm-12">
															<h4 class="info-text" style="text-align:center;"> Hello '.$register_name.' </h4>
														</div>
													
														<p class="col-sm-10 col-sm-offset-1 " style="text-align:center;"> You have been invited to the <strong>Jade Collections Loyalty Program</strong> system under the profile <strong>'.$profile_type.'</strong>.
														<br>Kindly complete your registration and create your account by following this link:
														</p>
														
														<h4 class="col-sm-10 col-sm-offset-1" style="text-align:center;"> <a href="https://indulgecard.co.ke/admin/jade_col/dash/user_registration.php?srdtfyuijknbvcfdrtyuijkmnbvcfdrtyuhjknbvcgfdrtyuijknmbvcfdyuhjknbvgfdtyhj='.$register_asignee.'&uhiufurebuidcuyegvfuihsdhjkshfdssdjkcdjhjgvgvvgvtfyvyvvgzxfgxvcjhuhuhuhoo='.$profile_type.'"> Click Here to register. </a></h4></div>
													</div>
												</div>
												
											   <div class="wizard-footer">	
														<h3>
															<div style="text-align:center;">
																<img src="https://indulgecard.co.ke/admin/API/JADE_API/logo.png"  height="90" width="190">
															</div>
														</h3>
												</div>
											</form>
										</div>
									</div> <!-- wizard container -->
								</div>
								</div> <!-- row -->							
								</div> <!--  big container -->

								</div>
							</div>
						</body>
				</html>	
			
			';
			$mail_message_txt = 'Welcome';
			$message_subject = 'Jade Collections Loyalty Program Admin Invitation.';
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
			->setUsername('granson@indulgencemarketing.co.ke')
			->setPassword('Power3942*');
			;

			// Create the Mailer using your created Transport
			$mailer = new Swift_Mailer($transport);

			// Create a message
			$message = (new Swift_Message($message_subject))
			  ->setFrom(['jadecollectionsrewards@indulgencemarketing.co.ke' => 'Jade Collections Loyalty System'])
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