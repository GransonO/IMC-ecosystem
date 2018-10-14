<?php
include 'core/init.php';
require '../../../vendors/composer_installed/autoload.php';


if((isset($_POST['recover_pass_name'])===true && empty($_POST['recover_pass_name'])=== false) &&
  (isset($_POST['recover_pass_email'])===true && empty($_POST['recover_pass_email'])=== false)){
	
	//Gets the date time
	date_default_timezone_set('Africa/Nairobi');
	$insert_date = date('Y-m-d H:i:s');
		
	$username = $_POST['recover_pass_name'];
	$email = $_POST['recover_pass_email'];
		
	
	
	if(user_exists($username) < 1){
			//User Does Not Exist.	
			echo 0;
		
	}else{
			
			$email_verify = email_verify($username,$email);	
			
		if($email_verify === 0){	
		
			//Combination error
			echo 1;
		
		}else{
			
		$profile_query = mysqli_query(Connect_Database(),"INSERT INTO `interpel_password_recovery` (`date`, `username`, `email`) VALUES ('$insert_date', '$username', '$email')");
		
		//SEND EMAIL WITH ALTERNATIVE PASSWORD
		$generated_password = substr(md5(rand(999,999999)),0,8);
		change_password($email_verify,$generated_password);
				
		recovery_mail($username,$email,$generated_password);

			//echo 2;
			} 
		}
	}else{
		
		echo 3;
	}	
	
function recovery_mail($username,$email,$generated_password){
	
				$mail_message_html =
							    '<!doctype html>
								<html lang="en">
								<body>
								<h3 style="text-align:center;">Hello '.$username.'</h3>
								<br>
								<p style="text-align:center;"> The password to your account has been changed as per your request.<br> Kindly use this as your password to access your account and change it in 24 hours: '.$generated_password.'
								<br>
								</p>
								<h4 style="text-align:center;"> <Strong><h2><a href="http://localhost/INDULGENCE_STANDARD/private_col/dash/recov/reset_pass.php?bvuiwhudfhuywvytefuydgsabdhuewxbigcftyfwytbfcyunhbrgfsdfsdgwvc='.$email.'&&jubgdiuwfy8trybkuhidjioftrmjtfbgyftufhhgyfgiftoihdwt674rtudifugo8jretg='.$generated_password.'&&dfrugphojwv7rntuydojfk9uhjg9fmyv5ytiuyvrbfvdihvgr='.$username.'"> Click here to reset the password </a></h2></Strong><br>
								</body>
								</html>';
								
			$mail_message_txt = 'Reset Success.';
			$message_subject = 'Interpel CFS Loyalty Program Password Recovery.';
	
	
			// Create the Transport
			$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, "ssl"))
			->setUsername('africaapeiron@gmail.com')
			->setPassword('Power3942*');
			;

			// Create the Mailer using your created Transport
			$mailer = new Swift_Mailer($transport);

			// Create a message
			$message = (new Swift_Message($message_subject))
			  ->setFrom(['africaapeiron@gmail.com' => 'Interpel CFS Loyalty Program Password Recovery'])
			  ->setTo($email)
			  
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

