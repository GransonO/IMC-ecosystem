<?php
include 'core/init.php';
require '../../../vendors/composer_installed/autoload.php';

//Gets the date time of redemption
	date_default_timezone_set('Africa/Nairobi');
	$redeem_date = date('Y-m-d H:i:s');
	
	$process_code = $_POST['process_code'];

if(empty($_POST) == false){
	$member_id = $_POST['reward_id'];
	$reward_option = $_POST['reward_option'];
	//Get Points assiciated with request
	$reward_query = "SELECT `threshold` AS threshold FROM `rewards_opt_tbl` WHERE `offer` = '$reward_option'";
	$query_result = mysqli_fetch_assoc(mysqli_query(Connect_Database(),$reward_query));
	$reward_request = $query_result['threshold'];
	
	
	$get_Points = "SELECT `total_points_available` AS points, `company_name` AS company, `redemptions_count` AS red_count, `total_points_redeemed` AS red_points FROM `member_loyalty_funds` WHERE `member_id` = '$member_id'"; 
	$points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$get_Points));
	
	$The_Company = $points_query['company'];
	//get points available
	$availablePoints =  $points_query['points'];
	//remaining points
	$rem_points = $availablePoints - $reward_request;
	//Redeemed Points add new redeemed
	$total_redeemed_points = $points_query['red_points'] + $reward_request;
	//redemption counts
	$current_count = $points_query['red_count'] + 1;
		
		//Opens redemption qualifier page
		if( $rem_points < 0){
		//Accounting error 
			echo 0;
		}else{
		//redeem users points		
		
		$username = $_SESSION['assignee']; 
		
		$update_loyalty = "UPDATE `member_loyalty_funds` SET `total_points_available` = '$rem_points', `total_points_redeemed` = '$total_redeemed_points', `redemptions_count` = '$current_count', `last_redeem_date` = '$redeem_date', `redeeemed_by` = '$username' WHERE `member_Id` = '$member_id'";
		//inserts into  table 
		if (mysqli_query(Connect_Database(), $update_loyalty)) {
			$redemption_ID = 'RD_'. date('ymdHis').$reward_request ;
		
			$insert_into_redemption = "INSERT INTO `member_redemption_transactions` 
			(`member_id`,`company_name`, `created_at`, `redeemed_by`, `redeemed_amount`, `redemption_id`)
			VALUES ('$member_id', '$The_Company', '$redeem_date', '$username', '$reward_request', '$redemption_ID');";
			
			$redemption_receipt = "INSERT INTO `redemption_receipts` 
			(`redemption_id`, `member_id`, `company_name`, `redemption_date`, `redemption_code`, `redemption_amount`, `balance`)
			VALUES (NULL, '$member_id', '$The_Company', '$redeem_date', '$redemption_ID', '$reward_request', '$rem_points');";
			
			
			$real = mysqli_query(Connect_Database(), $redemption_receipt)? 1:0 ;
			$deal = mysqli_query(Connect_Database(), $insert_into_redemption)? 1:0 ;
			// Perform a query, check for error

		if ($deal == 1 && $real == 1) {
				
				//redemption_mailer($member_id,$redeem_date,$username,$reward_request,$reward_option);
				
			/* 	$myObj = new \stdClass();
				$myObj->status = 'Transaction successful';
				$myObj->member = $member_id;
				$myObj->red_pts = $reward_request;
				$myObj->rem_pts = $rem_points;

				$myJSON = json_encode($myObj); */

			echo 1;	
		
			} else {
			
				echo 3;
			
				}
			}
		}
		
}

function redemption_mailer($member_id,$date_o_red,$redeemer,$points_redeemed,$item_redeemed){
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
														<h4 class="info-text" style="text-align:center;"> Redemption Alert, </h4>
													</div>
													
														<p class="col-sm-10 col-sm-offset-1 " style="text-align:center;"> 
														A redemption transaction for member id: '.$member_id.' has been processed by '.$redeemer.'.
														<br>
														Item rewarded : '.$item_redeemed.'
														<br>
														Total points redeemed : '.$points_redeemed.'
														<br>
														Redemption date/time : '.$date_o_red.'
														</p>
														</div>
											</div>
										</div>
									   <div class="wizard-footer">	
												<h3>
													<div style="text-align:center;">
														<img src="https://indulgecard.co.ke/admin/private_col/images/logo.png"  height="100" width="200">
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
								
			$mail_message_txt = 'Interpel Redemption '.$date_o_red;
			$message_subject = 'Interpel Redemption '.$date_o_red;
	
	
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
			  ->setTo(['granson@indulgencemarketing.co.ke', 'makena@indulgencemarketing.co.ke' => 'Makena', 'ruth@indulgencemarketing.co.ke' => 'Ruth', 'interpelrewards@indulgencemarketing.co.ke' => 'interpelrewards'])
			  
			// Give it a txt body
			  ->setBody($mail_message_txt)
			  
			// And optionally an html alternative body
			  ->addPart($mail_message_html, 'text/html');
			  ;

			// Send the message
			$result = $mailer->send($message,$failedRecipients);

			
}

?>

