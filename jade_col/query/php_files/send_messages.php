<?php
include 'core/init.php';
require_once('AfricasTalkingGateway.php');

	//Verifys user logged in 

   if((isset($_POST['message_body'])===true && empty($_POST['message_body'])=== false)){
			$message_title = $_POST['message_title'];
			$message_body = str_replace("'","",$_POST['message_body']);
			$message_recipients = $_POST['message_recipients'];
			$recipients_sql;
			$personalize = $_POST['personalize'];
			
			switch($message_recipients){
				case 'All';
				$recipients_sql = "SELECT * FROM `member`";
				
				break;
				
				case 'MEN';
				$recipients_sql = "SELECT * FROM `member` WHERE `gender` = 'Male'";
				
				break;
				
				case 'LADIES';
				$recipients_sql =  "SELECT * FROM `member` WHERE `gender` = 'Female'";
				
				break;
				
				case 'CUSTOM';
				$recipients_sql = '';
								
				break;
			};
			
						
			date_default_timezone_set('Africa/Nairobi');
			$message_date = date('Y-m-d H:i:s');
			$sender = $_SESSION['assignee']; 
	
			$customer_name;
			$customer_phone;					
			$customers_count;
			$name_array = array();
			$phone_array = array();
			$message_array = array();
			
			$query_result = mysqli_query(Connect_Database(),$recipients_sql);	
			while($recipients_query = mysqli_fetch_assoc($query_result)){
				
					$customer_name = $recipients_query['member_name'];
					$customer_phone = $recipients_query['phone'];
					$starter =  $customer_phone[0];
						
						if($starter == 0){
								$customer_phone = '+254'.substr($customer_phone,1);
							}
							//array_push($name_array, $customer_name); 
					
						if($personalize){
								$message = 'Dear '.$customer_name.', '.$message_body;
								array_push($message_array,$message);
								pass_to_api($customer_phone,$message);
								
							}else{
								$message = $customer_phone.' '.$message_body;							
								array_push($message_array,$message);		
								pass_to_api($customer_phone,$message);		
							
							}
							array_push($phone_array,$customer_phone);
							$customers_count = sizeof($phone_array);
				};
				//echo json_encode($message_array);
				
				//Add message details to the DB
				$insert_into_message_table = "INSERT INTO `message_table` (`message_date`, `message_title`, `message_body`, `recipients_count`, `sender`, `sent_report`) 
											VALUES ('$message_date', '$message_title', '$message_body', '$customers_count', '$sender', '0');";
				mysqli_query(Connect_Database(), $insert_into_message_table);

				echo 2;
				//echo $insert_into_message_table;
	}
	
	function pass_to_api($number,$message){
		
		// Specify your authentication credentials
		$username   = "indulgence_marketing_sms_app";
		$apikey     = "20133562722bfce859f423ae819382799d016268e3a21e0147c5727fbe608b80";

		// Specify the numbers that you want to send to in a comma-separated list
		// Please ensure you include the country code (+254 for Kenya in this case)
		$recipients = $number;

		// And of course we want our recipients to know what we really do
		$message    = $message;

		// Create a new instance of our awesome gateway class
		$gateway    = new AfricasTalkingGateway($username, $apikey);
		/*************************************************************************************
		  NOTE: If connecting to the sandbox:
		  1. Use "sandbox" as the username
		  2. Use the apiKey generated from your sandbox application
			 https://account.africastalking.com/apps/sandbox/settings/key
		  3. Add the "sandbox" flag to the constructor
		  $gateway  = new AfricasTalkingGateway($username, $apiKey, "sandbox");
		**************************************************************************************/

		// Any gateway error will be captured by our custom Exception class below, 
		// so wrap the call in a try-catch block
		date_default_timezone_set('Africa/Nairobi');
			$message_date = date('Y-m-d H:i:s');
						
		try 
		{ 

		// Thats it, hit send and we'll take care of the rest. 
		  $results = $gateway->sendMessage($recipients, $message);
			
		  foreach($results as $result) {
		  
			
			//status is either "Success" or "error message"
			$number = $result->number;
			$status = $result->status;
			$statuscode = $result->statusCode;
			$MessageId = $result->messageId;
			$Cost = $result->cost;
			
			$insert_into_results_table = "INSERT INTO `sms_responce_tbl` ( `date`, `number`, `status`, `statuscode`, `messageid`, `cost`) VALUES ('$message_date', '$number', '$status', '$statuscode', '$MessageId', '$Cost');";
			mysqli_query(Connect_Database(), $insert_into_results_table);
		  }
		  
		}
		catch ( AfricasTalkingGatewayException $e )
		{
		 
		  $insert_into_error_table = " INSERT INTO `sms_error_tbl` (`date`, `error_details`) VALUES ('$message_date ', '$e');";
		  mysqli_query(Connect_Database(), $insert_into_error_table);
		  
		  echo "Encountered an error while sending: ".$e->getMessage();
		}
		
}
	
?>