<?php

require_once("class.rest.php");
require 'connect.php';

class API extends REST {

    public function __construct() {
        parent::__construct();
    }

    public function processApi() {
	if (isset($this->_request['action'])) {
			$action_method = $this->_request['action'];
			switch($action_method){
				//for registration
				case 1:
				
					$func = strtolower(trim('registration'));
					if ((int) method_exists($this, $func) > 0){
					   $this->$func();
					}else{
					    $this->response($this->json(array('status' => 'false','status_code' => 404, 'message' => 'Only POST methods allowed.')), 404);
					}
				break;
				
				//for transactions
				case 2:
					
					$func = strtolower(trim('transactions'));
					if ((int) method_exists($this, $func) > 0){
					   $this->$func();
					}else{
					    $this->response($this->json(array('status' => 'false','status_code' => 404, 'message' => 'Only POST methods allowed.')), 404);
					}
				break;
				
				//for Ping
				case 3:
					
					$func = strtolower(trim('pingAction'));
					if ((int) method_exists($this, $func) > 0){
					   $this->$func();
					}else{
					    $this->response($this->json(array('status' => 'false','status_code' => 404, 'message' => 'Only POST methods allowed.')), 404);
					}
				break;
			
			}
		}
	}

	//Deals with the registration of the new members.
	private function registration(){

		if ($this->get_request_method() != "POST") {
			$this->response($this->json(array('status' => 'false','status_code' => 405, 'message' => 'Only POST methods allowed.')), 405);
		}
		
		if (isset($this->_request['token'])) {
			$token = $this->_request['token'];
			$key = 'AAAAB3NzaC1yc2EAAAABJQAAAQEA4iLjh8691V6SUtzh17mgRua5zTOBhQ+KMyE6YOc2pB7E4jl5oy66UNfrb1RGoqpnI8ni5KGypRUxAXqT+aCM3kGbsrNeTmw8tL0ThHGg2y3D3Kvto97FwX+dRhCDWdjntvr6deAwPoaxfvGMJU1u9/GH3MFLYDIVHE4dGc8uw87Gdaw18KV76YZE4ipkJ2MoupAnoi8KAtRYLct42smmGwRiMpp/dpSbrHkEJxqQyH/7iFAYnWYzbxVMZcghXwkjwWD51pAXlsz65rd/AyyAOOweT9jbvi/QxnDFBtHeLDoRHuTzO muVTx44UqmdSx13fJQIkm4M1MFjkPWpvh0Bw';

			if(!$token == $key){
				$this->response($this->json(array('status' => 'false','status_code' => 203, 'message' => 'You are not authorized to perform this request.')), 203);
			}else{
		
			if (isset($this->_request['account_id'])&& !empty($this->_request['account_id'])) {

					$account_id = $this->_request['account_id'];
					$name = $this->_request['name'];
					$phone = $this->_request['phone'];
					$email = $this->_request['email'];
					$entry_date = $this->_request['entry_date'];
					$last_visit = $this->_request['last_visit'];
					$customer_store_id = $this->_request['customer_store_id'];

					date_default_timezone_set('Africa/Nairobi');
					$Trans_date = date('Y-m-d H:i:s');	
					$month = date('m');	
					$year = date('Y');				
					
					$R_ID = "INSERT INTO `member_contact` (`created_at`, `created_by`,`customer_store_id`,`member_id`, `member_name`,`mobile_number`, `email_address`,`entry_date`,`last_visit`) VALUES ('$Trans_date', 'CC','$customer_store_id', '$account_id', '$name','$phone', '$email','$entry_date', '$last_visit');";
					
					$Rresult =  (mysqli_query(Connect_Database(),$R_ID))? 1:0;
					
					if($Rresult == 0){
							
						 $res = array('status' => 'false','status_code' => 404,'Content-Type' => 'application/x-www-form-urlencoded', 'message' => 'Member Registration error', 'Account ID' =>  $account_id, 'SQL ' =>  $R_ID, 'query_date' => $Trans_date.' Africa.Nairobi ');
						 $this->response($this->json($res), 200);
						 
					}else{
						 $res = array('status' => 'true','status_code' => 200, 'query_date' => $Trans_date.' Africa.Nairobi ','message' => ' Member registration successful.','Content-Type' => 'application/x-www-form-urlencoded','Account ID' =>  $account_id,'Registration Date'=> $entry_date);
						 $this->response($this->json($res), 200);
					}
			
				}
			}
		}

		$error = array('status' => 'false','status_code' => 405, 'message' => 'You are not authorized to perform this request.', 405);
        $this->response($this->json($error), 405);
	}

	//Deals with the posting of transactions and points calculations
	private function transactions(){

        if ($this->get_request_method() != "POST") {
            $this->response($this->json(array('status' => 'false','status_code' => 405, 'message' => 'Only POST methods allowed.')), 405);
        }
		
		if (isset($this->_request['token'])) {
			$token = $this->_request['token'];
			$key = 'AAAAB3NzaC1yc2EAAAABJQAAAQEA4iLjh8691V6SUtzh17mgRua5zTOBhQ+KMyE6YOc2pB7E4jl5oy66UNfrb1RGoqpnI8ni5KGypRUxAXqT+aCM3kGbsrNeTmw8tL0ThHGg2y3D3Kvto97FwX+dRhCDWdjntvr6deAwPooaxfvGMJU1u9/GH3MFLYDIVHE4dGc8uw87Gdaw18KV76YZE4ipkJ2MoupAnoi8KAtRYLct42smmGwRiMpp/dpSbrHkEJxqQyH/7iFAYnWYzbxVMZcghXwkjwWD51pAXlsz65rd/AyyAOOweT9jbvi/QxnDFBtHeLDoRHuTzOmuVTx44UqmdSx13fJQIkm4M1MFjkPWpvh0Bw==ssh-rsa AAAAB3NzaC1yc2EAAAABJQAAAQEAkRUApI9iCATvPuLa2hzJgvWU985gedPbsG551fAobaBIfDBm5FvISGUXmd72YhYFkCWd6H7CBwbBNjk9gcdb6xOcEorsabzX
			X1c8TSpUmSouitVuYLZs6g3BHwI8qWuAZYYlBn8/uwYPePgPeXfRQUVyrMFYW1O0QI7hVJB8KrhRB6xRMfDvzSqX4oTKtd82b8Koe7ccrLBzYH7n/JVwD9NC6mSRJFR/n7Ud03+rC4TCHeqtDJGWLz3SIzkFeywihTMIck6otCIF8TeXsgAS5yLYBL4jmomXb5lGipR8S5Cfzt/Yc5rwEAHNvrpf9Hr3TnsfbFb/pYcPiwlBL3eFzQ== rsa-key-20170404';
						
			if(!$token == $key){
				$this->response($this->json(array('status' => 'false','status_code' => 203, 'message' => 'You are not authorized to perform this request.')), 203);
			}else{
		//Transaction Process starts here
        if (isset($this->_request['store_id'])
		&& isset($this->_request['transaction_number'])
	
		&& !empty($this->_request['store_id']) 
		&&!empty($this->_request['transaction_number'])) {

				$store_id = $this->_request['store_id'];
				$transaction_number = $this->_request['transaction_number'];
				$transaction_time = $this->_request['transaction_time'];
				$customer_id = $this->_request['customer_id'];
				$total_spend = $this->_request['total_spend'];
				$cashier_id = $this->_request['cashier_id'];
				$phone_number = $this->_request['customer_phone_no'];

				date_default_timezone_set('Africa/Nairobi');
				$Trans_date = date('Y-m-d H:i:s');	
				$month = date('m');	
				$year = date('Y');				
				$tokens = ($total_spend * 0.1);
				
				$R_ID = "INSERT INTO `member_transactions` (`uploaded_by`, `uploaded_date`,`phonenumber`, `store_id`, `member_id`, `transaction_code`, `transaction_date`, `transaction_amount`, `loyalty_earned`, `month`, `year`) VALUES ('$cashier_id', '$Trans_date','$phone_number', '$store_id', '$customer_id', '$transaction_number','$transaction_time','$total_spend', '$tokens', '$month', '$year');"; 
				$Rresult =  (mysqli_query(Connect_Database(),$R_ID))? 1:0;
				
				if($store_id == 0){
						
					 $res = array('status' => 'false','status_code' => 404,'Content-Type' => 'application/x-www-form-urlencoded', 'message' => 'Data entry error', 'Store ID' =>  $store_id, 'Transaction Number' =>  $transaction_number, 'query_date' => $Trans_date.' Africa.Nairobi ');
					 $this->response($this->json($res), 200);
					 
				}else{
					 $res = array('status' => 'true','status_code' => 200, 'query_date' => $Trans_date.' Africa.Nairobi ','message' => ' Data posted successful.','Content-Type' => 'application/x-www-form-urlencoded','Store id' => $store_id, 'Transaction Number' => $transaction_number,'Spent Amount'=> $total_spend);
					 $this->response($this->json($res), 200);
					}
				}
		//Transaction Posted by here
			}
		}
		
        $error = array('status' => 'false','status_code' => 200, 'message' => 'There was an error in the transaction process. Verify all entries are correct');
        $this->response($this->json($error), 200);
    }


	
	//Deals with the registration of the new members.
	private function pingAction(){

		if ($this->get_request_method() != "POST") {
			$this->response($this->json(array('status' => 'false','status_code' => 405, 'message' => 'Only POST methods allowed.')), 405);
		}
		
		if (isset($this->_request['token'])) {
			$token = $this->_request['token'];
			$key = 'AAAAB3NzaC1yc2EAAAABJQAAAQEA4iLjh8691V6SUtzh17mgRua5zTOBhQ+KMyE6YOc2pB7E4jl5oy66UNfrb1RGoqpnI8ni5KGypRUxAXqT+aCM3kGbsrNeTmw8tL0ThHGg2y3D3Kvto97FwX+dRhCDWdjntvr6deAwPoaxfvGMJU1u9/GH3MFLYDIVHE4dGc8uw87Gdaw18KV76YZE4ipkJ2MoupAnoi8KAtRYLct42smmGwRiMpp/dpSbrHkEJxqQyH/7iFAYnWYzbxVMZcghXwkjwWD51pAXlsz65rd/AyyAOOweT9jbvi/QxnDFBtHeLDoRHuTzO muVTx44UqmdSx13fJQIkm4M1MFjkPWpvh0Bw';

			if(!$token == $key){
				$this->response($this->json(array('status' => 'false','status_code' => 203, 'message' => 'You are not authorized to perform this request.')), 203);
			}else{
		
			if (isset($this->_request['store_id'])&& !empty($this->_request['store_id'])) {

					$store_id = $this->_request['store_id'];

					date_default_timezone_set('Africa/Nairobi');
					$Trans_date = date('Y-m-d H:i:s');	
					$month = date('m');	
					$year = date('Y');				
					
					$R_ID = "INSERT INTO `ping_file` (`store_id`, `ping_time`) VALUES ('$store_id', '$Trans_date');;";
					
					$Rresult =  (mysqli_query(Connect_Database(),$R_ID))? 1:0;
					
					if($Rresult == 0){
							
						 $res = array('status' => 'false','status_code' => 404,'Content-Type' => 'application/x-www-form-urlencoded', 'message' => 'Ping Test error', 'Store ID' =>  $store_id, 'query_date' => $Trans_date.' Africa.Nairobi ');
						 $this->response($this->json($res), 200);
						 
					}else{
						 $res = array('status' => 'true','status_code' => 200, 'Ping Date' => $Trans_date.' Africa.Nairobi ','message' => ' Ping successful.','Content-Type' => 'application/x-www-form-urlencoded','Store ID' =>  $store_id);
						 $this->response($this->json($res), 200);
					}
			
				}
			}
		}

		$error = array('status' => 'false','status_code' => 405, 'message' => 'You are not authorized to perform this request.', 405);
        $this->response($this->json($error), 405);
	}
	
 private function json($data) {
        if (is_array($data)) {
            return json_encode($data);
        }
    }

}

$api = new API;
$api->processApi();
?>