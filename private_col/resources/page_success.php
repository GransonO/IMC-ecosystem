<?php
include 'init.php';

//Verifys user logged in 
$USER_ID = $_SESSION['ID'];
	if($USER_ID === null){
		//Show loggin error
	header('Location: login_error.php');
	
	}else{
		//do nothing
	}
	
$User_Data = "SELECT `username` AS Name,`email` AS mail FROM privileged_users WHERE `ID` = '$USER_ID'"; 
$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
$username = $U_query['Name'];
$the_mail = $U_query['mail'];

		$AvailablePoints =  $_SESSION['Available'];
		$AmountRedeemed = $_SESSION['redeemed_value'];
		$rem_points = $_SESSION['Available'] - $AmountRedeemed;
		$total_redeemed_points = $_SESSION['red_points'] + $AmountRedeemed;
		$current_count = $_SESSION['red_count'] + 1;
		$The_Company = $_SESSION['company'];
		$Redeem_user_id = $_SESSION['member'];
		$clients_mail = $_SESSION['red_mail'];
		
		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$redeem_date = date('Y-m-d H:i:s');
		$redemption_ID = 'RD_'. date('ymdHis').$AmountRedeemed ;

		//Sends the redemption mail
		$message = '<html><body>
			<h2 style="color:#f40;">Hello '.$The_Company. '</br> Member ID : '.$Redeem_user_id.'</h2>
			<p style="color:#000;font-size:15px;">This mail stands as verification of the following transaction:</p>
			<p style="color:#080;font-size:16px;"><h2>Details :</h2></p>
			<p style="color:#080;font-size:16px;"><h2>Redemption ID : </h2><h3>'.$redemption_ID.'</br></p>
			<p style="color:#080;font-size:16px;"><h2>Redemption Date & Time : </h2>'.$redeem_date.'</br></p>
			<p style="color:#080;font-size:16px;"><h2>Redemption Amount : </h2>'.$AmountRedeemed.'</br></p>
			<p style="color:#080;font-size:16px;"><h2>Loyalty Balance : </h2>'.$rem_points.'</br></p>
			<p style="color:#080;font-size:15px;"> <Strong><h3>Transaction executed by : </h3>'.$username.'</Strong></br></p>
			<p style="color:#080;font-size:15px;"> <Strong><h3>*** We appreciate you working with us *** </h3></Strong></br></p>
			</body></html>';
				
			// Change the corumdeveloper@gmail to $clients_mail;
			//redemption_mail('oyombegranson@gmail.com','granson@indulgencemarketing.co.ke','corumdeveloper@gmail.com','Redemption Verifier Mail',$message);
			
	//echo $message;
if (empty($_POST['submitted']) === false && $_POST['submitted'] === 'GENERATE'){
	
	header('Location: Generated_Receipt.php');		
		
}
			
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Indulgence Point | </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number">Success !</h1>
              <h2>Redemption Process Complete</h2>
              <p><a href="support.php?mode=Redemption Error">Incase of erroneous entry,aknowledge by an hour.</a>
              </p>
              <div class="mid_center">	
			  <form action="" method="post"> 
				<div class="form-group">
					<div class="col-md-12 col-sm-12 col-xs-12 "  style="text-align: center;">
                          <button type="submit" name="submitted" value="GENERATE" class="btn btn-primary">Generate receipt</button>
                  </div>
                  </div>
				  </form>
				  </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
  </body>
</html>