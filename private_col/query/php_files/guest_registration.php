<?php
//include the init file for all functions
include 'core/init.php';
require '../../../vendors/composer_installed/autoload.php';
		
if(empty($_POST)===false){

$profile = $_SESSION['profile']; 
$invitor_name = $_SESSION['assignee']; 
				
		$guest_register_first_name = $_POST['guest_register_first_name'];
		$guest_register_last_name = $_POST['guest_register_last_name'];
		$guest_register_email = $_POST['guest_register_email'];
		$guest_register_phone = $_POST['guest_register_phone'];	
		$guest_register_id_card = $_POST['guest_register_id_card'];	
		$guest_gender_type = $_POST['guest_gender_type'];	
		
		//Get registrar details
		$verify_query = "SELECT `member_id` as ID FROM `member_contact` WHERE `email_address` = '$guest_register_email';";
		$verify_data = mysqli_fetch_assoc(mysqli_query(Connect_Database(), $verify_query));
		$verify_result= $verify_data['ID'];
		if($verify_result){
			//Member present(Double Entry)
			echo 1;
			}else{
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$create_date = date('Y-m-d H:i:s');
		
		$gen_member_id = rand(999,99999).date('Y');
						
		$insert_into_members = "INSERT INTO `member_contact`
		(`created_at`, `created_by`,`member_id`, `company_name`, `poc_name`,`mobile_number`, `email_address`,`identification_number`,`gender`) VALUES 
		('$create_date', '$invitor_name', '$gen_member_id', '$guest_register_first_name', 
		'$guest_register_first_name $guest_register_last_name','$guest_register_phone', '$guest_register_email','$guest_register_id_card','$guest_gender_type');";
		
		$zeal = mysqli_query(Connect_Database(), $insert_into_members)? 2:0 ;
				
		if($zeal === 2){
			//Posting success
			registration_mail($guest_register_first_name,$guest_register_email,$gen_member_id);
			
		}else{
			//posting failed
			echo 0;
		} 
			}

}

function registration_mail($guest_register_first_name,$guest_register_email,$gen_member_id){
	
				$mail_message_html =
								'
<!doctype html>
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
											<img src="https://indulgecard.co.ke/admin/private_col/images/kongoni_logo.png"  height="80" width="160">
										</div>
									</h3>
								</div>

										<div class="tab-content">
																		
											<div class="tab-pane" id="welcome">
												<div class="row">
													<div class="col-sm-12">
														<h4 class="info-text" style="text-align:center;"> Hello '.$guest_register_first_name.' </h4>
													</div>
													
														<p class="col-sm-10 col-sm-offset-1 " style="text-align:center;"> We cordially welcome you to the <strong>Private Collection Fahari Club</strong>.
														<br>Our desire is to give you the best of what we have to offer and make your experience with us worth while. 
														<br>As a member, you are entitled to all the benefits as discussed in the programs flyer. 
														<br>We are delighted to welcome you to the program and are ready to serve you.
														</p>
														<h4 class="col-sm-10 col-sm-offset-1 " style="text-align:center;"> Your registration number is:
														<br>
														<strong>'.$gen_member_id.'</strong></h4>
														<p class="col-sm-10 col-sm-offset-1" style="text-align:center;">Kindly provide this number during your subsequent visits.</p>
												</div>
											</div>
										</div>
									   <div class="wizard-footer">	
												<h3>
													<div style="text-align:center;">
														<img src="https://indulgecard.co.ke/admin/private_col/images/my_footer.png"  height="100" width="500">
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
	</html>		';
								
			$mail_message_txt = 'Welcome '.$guest_register_first_name;
			$message_subject = 'Welcome '.$guest_register_first_name;
	
	
			// Create the Transport
			$transport = (new Swift_SmtpTransport('mail.indulgencemarketing.co.ke', 465, "ssl"))
			->setUsername('kongoniloyalty@indulgencemarketing.co.ke')
			->setPassword('1M&Nklai12018');
			;

			// Create the Mailer using your created Transport
			$mailer = new Swift_Mailer($transport);

			// Create a message
			$message = (new Swift_Message($message_subject))
			  ->setFrom(['kongoniloyalty@indulgencemarketing.co.ke' => 'Naivasha Kongoni Lodge Fahari Program'])
			  ->setTo($guest_register_email)
			  
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
			
}
?>