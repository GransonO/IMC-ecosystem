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
		
		if (empty($_POST['submitted']) === false && $_POST['submitted'] === 'DONE'){
		//Get Available

		// 1. Updated redeemed points by adding to the total redeemed points
		// 2. Updated Available points by subtracting from the total Available points
		// 3. Updated redeemed points count by adding to the redemption count
		// 4. Update last redeem date 
		// 5. Update redeemed_by column
		if( $rem_points < 0){
			header('Location: account_error.php');
			
		}
		else{
			
		$update_loyalty = "UPDATE `member_loyalty_funds` SET `total_points_available` = '$rem_points', `total_points_redeemed` = '$total_redeemed_points', `redemptions_count` = '$current_count', `last_redeem_date` = '$redeem_date', `redeeemed_by` = '$username' WHERE `member_Id` = '$Redeem_user_id'";
		//inserts into  table 
		if (mysqli_query(Connect_Database(), $update_loyalty)) {
			$redemption_ID = 'RD_'. date('ymdHis').$AmountRedeemed ;
		
			$insert_into_redemption = "INSERT INTO `member_redemption_transactions` 
			(`member_id`,`company_name`, `created_at`, `redeemed_by`, `redeemed_amount`, `redemption_id`)
			VALUES ('$Redeem_user_id', '$The_Company', '$redeem_date', '$username', '$AmountRedeemed', '$redemption_ID');";
			
			$redemption_receipt = "INSERT INTO `redemption_receipts` 
			(`redemption_id`, `member_id`, `company_name`, `redemption_date`, `redemption_code`, `redemption_amount`, `balance`)
			VALUES (NULL, '$Redeem_user_id', '$The_Company', '$redeem_date', '$redemption_ID', '$AmountRedeemed', '$rem_points');";
			
			
			$real = mysqli_query(Connect_Database(), $redemption_receipt)? 1:0 ;
			$deal = mysqli_query(Connect_Database(), $insert_into_redemption)? 1:0 ;
			// Perform a query, check for error

		if ($deal == 1 && $real == 1) {
			
			header('Location: page_success.php');
		
			} else {
			
			die('Could not enter data: ' . mysqli_error(Connect_Database()));
			
				}
			}
		}
	} else if (empty($_POST['not_submitted']) === false && $_POST['not_submitted'] === 'cancel'){
		
		header('Location: home.php');
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

  <body class="nav-md" style="background-color:#00F3FF;">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number">Performing Action:</h1>
			  <p><h2>Redemption of customers' points.</h2></p></br>			  
              <h2>Member Identification Key</h2>
              <h1> <?php echo $Redeem_user_id; ?> </h1>
			  
              <h2>Amount Available in Account</h2>		
              <h1> <?php echo $AvailablePoints; ?> </h1>
			  
              <h2>Redemption Amount Requested</h2>
              <h1> <?php echo $AmountRedeemed; ?> </h1>
			  
              <h2>Account Balance</h2>
              <h1> <?php echo $rem_points; ?> </h1>
			  
              <p><a href="#">Confirm the details and approve to complete the operation </a>
              </p>
              <div class="mid_center">
			  <form action="" method="post"> 
				<div class="form-group">
					<div class="col-md-12 col-sm-12 col-xs-12 "  style="text-align: center;">
                          <button type="submit" name="submitted" value="DONE" class="btn btn-success">Approve</button>
                          <button type="submit" name="not_submitted" value="cancel" class="btn btn-primary">Cancel</button>
                  </div>
                  </div>
				  </form>
                </div>
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