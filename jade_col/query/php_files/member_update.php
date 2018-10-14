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
				
			//1. Update members Table
			$member_table =	"UPDATE `member` SET `member_id` = '$member_id', `company_name` = '$register_company_name' WHERE `member_id` = '$member_id';";
			
			//2. Update member contacts
			$contacts_table = "UPDATE `member_contact` SET`member_id` = '$member_id', `company_name` = '$register_company_name', `poc_name` = '$register_poc_name', `poc_is_changed` = 1, `poc_changed_by` = '$username', 
			`mobile_number` = '$register_phone_number', `email_address` = '$register_email_address',`contacts_is_changed` = 1, `contacts_changed_by` = '$username'  WHERE `member_id` = '$member_id';";
			
			//3. Update Loyalty funds			
			$loyalty_funds = "UPDATE `member_loyalty_funds` SET `member_id` = '$member_id', `company_name` = '$register_company_name' WHERE `member_id` = '$member_id';";	
							
			$mem_tab = mysqli_query(Connect_Database(), $member_table)? 1:0 ;
			$con_tab = mysqli_query(Connect_Database(), $contacts_table)? 1:0 ;
			$loy_tab = mysqli_query(Connect_Database(), $loyalty_funds) ? 1:0 ;
							
			if($mem_tab === 1 && $con_tab === 1 && $loy_tab === 1){
				//Posting success
				registration_mailer($member_id,$register_company_name,$register_poc_name,$register_phone_number,$register_email_address,$username);
				
			}else{
				//posting failed
				echo $mem_tab .' '.$con_tab.' '.$loy_tab;
				//echo $member_table .' '.$contacts_table.' '.$loyalty_funds;
			} 
}		


function registration_mailer($member_id,$register_company_name,$register_poc_name,$register_phone_number,$register_email_address,$username){
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
														The contacts for member: <strong>'.$register_company_name.'</strong> have been updated by <strong>'.$username.'</strong><br> New details:<br>
														<strong>
														<br> Member ID      : '.$member_id.'	
														<br>	
														<br> Company Name   : '.$register_company_name.'	
														<br>
														<br> Contact Person : '.$register_poc_name.'	
														<br>
														<br> Email Address  : '.$register_email_address.'	
														<br>
														<br> Phone Number   : '.$register_phone_number.'
														<br>
														</strong>
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
								
			$mail_message_txt = 'Member '.$member_id.' Contacts Change Notification';
			$message_subject = 'Member Contacts Change Notification';
	
	
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
			  ->setTo(['makena@indulgencemarketing.co.ke','interpelrewards@indulgencemarketing.co.ke'])
			  
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