<?php
//include the init file for all functions
include 'core/init.php';
require '../../../vendors/composer_installed/autoload.php';
		
if(empty($_POST)===false){

		$profile = $_SESSION['profile']; 
		$username = $_SESSION['assignee']; 
				
		$member_id = $_POST['member_id'];
		$register_company_name = $_POST['register_company_name'];
		$register_poc_name = $_POST['register_poc_name'];
		$register_phone_number = $_POST['register_phone_number'];
		$register_email_address = $_POST['register_email_address'];
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$create_date = date('Y-m-d H:i:s');
				
			//1. Insert to members Table
			$member_table = "INSERT INTO `member` 
			(`is_active`, `created_at`, `created_by`, `is_deleted`, `member_id`, `company_name`, `date_deleted`, `deleted_by`, `reason_deleted`, `row_comment`) VALUES 
			('1', '$create_date', '$username', '0', '$member_id', '$register_company_name', NULL, 'AA', 'AA', 'AA');";
			
			
			//2. Insert to member contacts
			$contacts_table = "INSERT INTO `member_contact` 
			(`is_active`, `created_at`, `created_by`, `is_deleted`, `member_id`, `company_name`, `poc_name`, `poc_is_changed`, `poc_changed_by`, `mobile_number`, `email_address`, `password`, `contacts_is_changed`, `contacts_changed_by`, `date_deleted`, `contacts_deleted_by`, `reason_deleted`, `row_comment`) VALUES 
			('1', '$create_date', '$username', '0', '$member_id', '$register_company_name', '$register_poc_name', '0', 'AA', '$register_phone_number', '$register_email_address', 'unspecified', '0', 'AA', NULL, 'AA', 'AA', 'AA');";
			
			
			//3. Insert to member Loyalty funds			
			$loyalty_funds = "INSERT INTO `member_loyalty_funds`(`created_at`, `max_trans_date`, `created_by`, `is_deleted`, `member_id`, `company_name`, `loyalty_value_earned`, `total_points_available`, `total_points_redeemed`, `redemptions_count`, `total_purchase`, `expired_loyalty`, `last_redeem_date`, `redeeemed_by`, `is_active`, `is_credit`, `deleted_by`, `date_deleted`, `reason_deleted`, `row_comment`) VALUES
			('$create_date', NULL, '$username', '0', '$member_id', '$register_company_name', '0', '0', '0', '0', '0', '0', NULL, NULL, '1', '0', 'aa', NULL, 'aa', 'aa');";	
							
			$mem_tab = mysqli_query(Connect_Database(), $member_table)? 1:0 ;
			$con_tab = mysqli_query(Connect_Database(), $contacts_table)? 1:0 ;
			$loy_tab = mysqli_query(Connect_Database(), $loyalty_funds) ? 1:0 ;
							
			if($mem_tab === 1 && $con_tab === 1 && $loy_tab === 1){
				//Posting success
				registration_mailer($member_id,$register_company_name,$register_poc_name,$register_phone_number,$register_email_address);
				
			}else{
				//posting failed
				echo $mem_tab .' '.$con_tab.' '.$loy_tab;
			} 
}		


function registration_mailer($member_id,$register_company_name,$register_poc_name,$register_phone_number,$register_email_address){
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
											<img src="https://indulgecard.co.ke/interpel/interpelrewards/production/images/company_logo.jpg"  height="80" width="160">
										</div>
									</h3>
								</div>

										<div class="tab-content">
																		
											<div class="tab-pane" id="welcome">
												<div class="row">
													<div class="col-sm-12">
														<h4 class="info-text" style="text-align:center; color:blue;"> INTERPEL CFS LOYALTY PROGRAM </h4>
													</div>
																					
														<p class="col-sm-10 col-sm-offset-1 " style="text-align:center;"> 
														We at Interpel CFS cordially to welcome yo to our loyalty program where you get rewarded for every transaction you make with us.
														<strong>
														<br> Member ID      : '.$member_id.'	
														<br> Company Name   : '.$register_company_name.'
														<br> Contact Person : '.$register_poc_name.'
														<br> Email Address  : '.$register_email_address.'
														<br> Phone Number   : '.$register_phone_number.'
														<br>
														</strong>
														We look forward to building a lasting and fruit full relationship with you. 
														</p>
														</div>
											</div>
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
								
			$mail_message_txt = 'Welcome to the Interpel CFS Loyalty Program';
			$message_subject = 'Welcome to the Interpel CFS Loyalty Program';
	
	
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
			  ->setTo([$register_email_address,'interpelrewards@indulgencemarketing.co.ke'])
			  
			// Give it a txt body
			  ->setBody($mail_message_txt)
			  
			// And optionally an html alternative body
			  ->addPart($mail_message_html, 'text/html');
			  ;

			// Send the message
			$result = $mailer->send($message,$failedRecipients);
			echo 1;
			
}
?>