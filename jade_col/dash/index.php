<?php
//include the init file for all functions
include '../query/php_files/core/init.php';
$Available_Points = '000';
$Company_name = '';
$Redeemptions_performed = 0;
$Redeemed_points = 0;
$TheID = '';

date_default_timezone_set('Africa/Nairobi');
$current_date = date('d/m/Y');

//Verifys user logged in 
$USER_ID = $_SESSION['ID'];
	if($USER_ID === null){
		//Show login error
	header('Location: ../ini/login_error.html');
	}else{
		//do nothing
	}

//Get top Five Values
$query = "SELECT *
          FROM `member_loyalty_funds`
          ORDER BY `redemptions_count` DESC LIMIT 5;";
$result = mysqli_query(Connect_Database(),$query);
$arrayofrows = array();
/* 
{//USED WHEN DATA PRESENT

		while($row = mysqli_fetch_all($result))
		{
		   $arrayofrows = $row;
		}
		//Active Performers based on Redemptions And Purchase
		{
		$first_name = $arrayofrows[0][6];
		$first_id = $arrayofrows[0][5];
		$first_amount = $arrayofrows[0][9];
		$firstQuery = "SELECT `redemptions_count` AS red_count FROM `member_loyalty_funds` WHERE `member_id` = '$first_id'"; 
		$F_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$firstQuery));
		$F_count = number_format($F_query['red_count']);

		$firstpur = "SELECT Count(`transaction_amount`) AS red_count FROM `member_transactions` WHERE `member_id` = '$first_id'"; 
		$Fp_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$firstpur));
		$F_pur = number_format($Fp_query['red_count']);

		$sec_name = $arrayofrows[1][6];
		$sec_id = $arrayofrows[1][5];
		$sec_amount = $arrayofrows[1][9];
		$secQuery = "SELECT `redemptions_count` AS red_count FROM `member_loyalty_funds` WHERE `member_id` = '$sec_id'"; 
		$S_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$secQuery));
		$S_count = number_format($S_query['red_count']);

		$secpur = "SELECT Count(`transaction_amount`) AS red_count FROM `member_transactions` WHERE `member_id` = '$sec_id'"; 
		$Sp_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$secpur));
		$S_pur = number_format($Sp_query['red_count']);

		$third_name = $arrayofrows[2][6];
		$third_id = $arrayofrows[2][5];
		$third_amount = $arrayofrows[2][9];
		$thirdQuery = "SELECT `redemptions_count` AS red_count FROM `member_loyalty_funds` WHERE `member_id` = '$third_id'"; 
		$T_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$thirdQuery));
		$T_count = number_format($T_query['red_count']);

		$thirdpur = "SELECT Count(`transaction_amount`) AS red_count FROM `member_transactions` WHERE `member_id` = '$third_id'"; 
		$Tp_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$thirdpur));
		$T_pur = number_format($Tp_query['red_count']);

		$forth_name = $arrayofrows[3][6];
		$forth_id = $arrayofrows[3][5];
		$forth_amount = $arrayofrows[3][9];
		$forthQuery = "SELECT `redemptions_count` AS red_count FROM `member_loyalty_funds` WHERE `member_id` = '$forth_id'"; 
		$FO_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$forthQuery));
		$FO_count = number_format($FO_query['red_count']);

		$forthtpur = "SELECT Count(`transaction_amount`) AS red_count FROM `member_transactions` WHERE `member_id` = '$forth_id'"; 
		$FO_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$forthtpur));
		$FO_pur = number_format($FO_query['red_count']);

		$fifth_name = $arrayofrows[4][6];
		$fifth_id = $arrayofrows[4][5];
		$fifth_amount = $arrayofrows[4][9];
		$fifthQuery = "SELECT `redemptions_count` AS red_count FROM `member_loyalty_funds` WHERE `member_id` = '$fifth_id'"; 
		$FI_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$fifthQuery));
		$FI_count = number_format($FI_query['red_count']);

		$fifthpur = "SELECT Count(`transaction_amount`) AS red_count FROM `member_transactions` WHERE `member_id` = '$fifth_id'"; 
		$FI_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$fifthpur));
		$FI_pur = number_format($FI_query['red_count']);
		}
		// Get the top points earners
		{
		$points_query = "SELECT *
				  FROM `member_loyalty_funds`
				  ORDER BY `loyalty_value_earned` DESC LIMIT 5;";
		$points_result = mysqli_query(Connect_Database(),$points_query);
		$points_arrayofrows = array();
		while($row = mysqli_fetch_all($points_result))
		{
		   $points_arrayofrows = $row;
		}
		$first_points_name = $points_arrayofrows[0][6];
		$first_points_id = $points_arrayofrows[0][5];
		$first_points_amount = number_format($points_arrayofrows[0][7]);

		$first_points_Query = "SELECT COUNT(`transaction_code`) AS red_count FROM `member_transactions` WHERE `member_id` = '$first_points_id'"; 
		$F_points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$first_points_Query));
		$F_points_count = number_format($F_points_query['red_count']);

		$sec_points_name = $points_arrayofrows[1][6];
		$sec_points_id = $points_arrayofrows[1][5];
		$sec_points_amount = number_format($points_arrayofrows[1][7]);

		$sec_points_Query = "SELECT COUNT(`transaction_code`) AS red_count FROM `member_transactions` WHERE `member_id` = '$sec_points_id'"; 
		$sec_points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$sec_points_Query));
		$S_points_count = number_format($sec_points_query['red_count']);

		$third_points_name = $points_arrayofrows[2][6];
		$third_points_id = $points_arrayofrows[2][5];
		$third_points_amount = number_format($points_arrayofrows[2][7]);

		$third_points_Query = "SELECT COUNT(`transaction_code`) AS red_count FROM `member_transactions` WHERE `member_id` = '$third_points_id'"; 
		$third_points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$third_points_Query));
		$T_points_count = number_format($third_points_query['red_count']);

		$forth_points_name = $points_arrayofrows[3][6];
		$forth_points_id = $points_arrayofrows[3][5];
		$forth_points_amount = number_format($points_arrayofrows[3][7]);

		$forth_points_Query = "SELECT COUNT(`transaction_code`) AS red_count FROM `member_transactions` WHERE `member_id` = '$forth_points_id'"; 
		$forth_points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$forth_points_Query));
		$FO_points_count = number_format($forth_points_query['red_count']);

		$fifth_points_name = $points_arrayofrows[4][6];
		$fifth_points_id = $points_arrayofrows[4][5];
		$fifth_points_amount = number_format($points_arrayofrows[4][7]);

		$fifth_points_Query = "SELECT COUNT(`transaction_code`) AS red_count FROM `member_transactions` WHERE `member_id` = '$fifth_points_id'"; 
		$fifth_points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$fifth_points_Query));
		$FI_points_count = number_format($fifth_points_query['red_count']);

		}
		//Get bottom Five Values
		{
		$Total_C_Query = "SELECT COUNT(`member_id`) AS red_count FROM `member_loyalty_funds` WHERE `loyalty_value_earned` > 0 AND `expired_loyalty` < 1"; 
		$total_c_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Total_C_Query));
		$total_c_count = number_format($total_c_query['red_count']);
			
		$L_query = "SELECT *
				  FROM `member_loyalty_funds` WHERE `loyalty_value_earned` > 0 AND `expired_loyalty` < 1 ORDER BY `loyalty_value_earned` ASC LIMIT 5;";
		$L_result = mysqli_query(Connect_Database(),$L_query);
		$arrayof_last_rows = array();
		while($row = mysqli_fetch_all($L_result))
		{
		   $arrayof_last_rows = $row;
		}

		$last_name = $arrayof_last_rows[0][6];
		$last_id = $arrayof_last_rows[0][5];
		$last_amount = $arrayof_last_rows[0][7];

		$L_points_Query = "SELECT COUNT(`transaction_code`) AS red_count FROM `member_transactions` WHERE `member_id` = '$last_id'"; 
		$L_points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$L_points_Query));
		$L_points_count = number_format($L_points_query['red_count']);

		$sec_last_name = $arrayof_last_rows[1][6];
		$sec_last_id = $arrayof_last_rows[1][5];
		$sec_last_amount = $arrayof_last_rows[1][7];

		$sec_last_points_Query = "SELECT COUNT(`transaction_code`) AS red_count FROM `member_transactions` WHERE `member_id` = '$sec_last_id'"; 
		$sec_last_points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$sec_last_points_Query));
		$sec_last_points_count = number_format($sec_last_points_query['red_count']);

		$third_last_name = $arrayof_last_rows[2][6];
		$third_last_id = $arrayof_last_rows[2][5];
		$third_last_amount = $arrayof_last_rows[2][7];

		$third_last_points_Query = "SELECT COUNT(`transaction_code`) AS red_count FROM `member_transactions` WHERE `member_id` = '$third_last_id'"; 
		$third_last_points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$third_points_Query));
		$third_last_points_count = number_format($third_points_query['red_count']);

		$forth_last_name = $arrayof_last_rows[3][6];
		$forth_last_id = $arrayof_last_rows[3][5];
		$forth_last_amount = $arrayof_last_rows[3][7];

		$forth_last_points_Query = "SELECT COUNT(`transaction_code`) AS red_count FROM `member_transactions` WHERE `member_id` = '$forth_last_id'"; 
		$forth_last_points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$forth_last_points_Query));
		$forth_last_points_count = number_format($forth_last_points_query['red_count']);

		$fifth_last_name = $arrayof_last_rows[4][6];
		$fifth_last_id = $arrayof_last_rows[4][5];
		$fifth_last_amount = $arrayof_last_rows[4][7];

		$fifth_last_points_Query = "SELECT COUNT(`transaction_code`) AS red_count FROM `member_transactions` WHERE `member_id` = '$fifth_last_id'"; 
		$fifth_last_points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$fifth_last_points_Query));
		$fifth_last_points_count = number_format($fifth_last_points_query['red_count']);
		}

}*/

{//USED WHEN NO DATA PRESENT
	//Active Performers based on Redemptions And Purchase
	{
	$first_name = "C1";
	$first_id = "ID1";
	$first_amount = 0;
	$F_count = number_format(0);
	$F_pur = number_format(0);

	$sec_name = "C2";
	$sec_id = "ID2";
	$sec_amount = 0;
	$S_count = number_format(0);
	$S_pur = number_format(0);

	$third_name = "C3";
	$third_id = "ID3";
	$third_amount = 0;
	$T_count = number_format(0);
	$T_pur = number_format(0);

	$forth_name = "C4";
	$forth_id = "ID4";
	$forth_amount = 0;
	$FO_count = number_format(0);
	$FO_pur = number_format(0);

	$fifth_name = "C5";
	$fifth_id = "ID5";
	$fifth_amount = 0;
	$FI_count = number_format(0);
	$FI_pur = number_format(0);
	}
	// Get the top points earners
	{

	$first_points_name = "C1";
	$first_points_id = "ID1";
	$first_points_amount = number_format(0);
	$F_points_count = number_format(0);

	$sec_points_name = "C2";
	$sec_points_id = "ID2";
	$sec_points_amount = number_format(0);
	$S_points_count = number_format(0);

	$third_points_name = "C3";
	$third_points_id = "ID3";
	$third_points_amount = number_format(0);
	$T_points_count = number_format(0);

	$forth_points_name = "C4";
	$forth_points_id = "ID4";
	$forth_points_amount = number_format(0);
	$FO_points_count = number_format(0);

	$fifth_points_name = "C5";
	$fifth_points_id = "ID5";
	$fifth_points_amount = number_format(0);
	$FI_points_count = number_format(0);

	}
	//Get bottom Five Values
	{
	$last_name = "C1";
	$last_id = "ID1";
	$last_amount = 0;
	$L_points_count = number_format(0);

	$sec_last_name = "C2";
	$sec_last_id = "ID2";
	$sec_last_amount = 0;
	$sec_last_points_count = number_format(0);

	$third_last_name = "C3";
	$third_last_id = "ID3";
	$third_last_amount = 0;
	$third_last_points_count = number_format(0);

	$forth_last_name = "C4";
	$forth_last_id = "ID4";
	$forth_last_amount = 0;
	$forth_last_points_count = number_format(0);

	$fifth_last_name = "C5";
	$fifth_last_id = "ID5";
	$fifth_last_amount = 0;
	$fifth_last_points_count = number_format(0);
	}

}
//Get User Details
$User_Data = "SELECT * FROM privileged_users WHERE `password` = '$USER_ID'"; 
$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
$username = $U_query['username'];
$status = $U_query['redeemer'];
$profile = $U_query['profile'];

//Queries customers total purchase
$purchase_query = "SELECT SUM(`transaction_amount`) AS total_purchase FROM member_transactions where `transaction_date` >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
$pquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$purchase_query));
$english_format_number = $pquery['total_purchase'];
$purchase = number_format($english_format_number);

//Queries customers total Available loyalty
$points_query = "SELECT SUM(`loyalty_value_earned`) AS total_loyalty FROM member_loyalty_funds";
$lquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$points_query));
$loyalty_format_number = $lquery['total_loyalty'];
$loyalty = number_format($loyalty_format_number);

//Queries customers total redeemed loyalty
$redeemed_query = "SELECT SUM(`total_points_redeemed`) AS redeemed_loyalty FROM member_loyalty_funds";
$rquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$redeemed_query));
$redeem_format_number = $rquery['redeemed_loyalty'];
$redeem = number_format($redeem_format_number);

//Queries customers total Expired loyalty
$expired_query = "SELECT SUM(`expired_loyalty`) AS expired_loyalty FROM member_loyalty_funds";
$equery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$expired_query));
$expired_format_number = $equery['expired_loyalty'];
$expired = number_format($expired_format_number);

//Function get month and purchase value
function get_plot_data($i,$y){

	$p_month = $i;
	$p_year =  date('Y') - $y;
	
	$plot_data = "SELECT SUM(`transaction_amount`) AS trans FROM member_transactions WHERE `month` = '$p_month' AND `year` = '$p_year'"; 
	$P_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$plot_data));
	$Transaction = $P_query['trans'];
	return $Transaction;
	
}
//Function get month and loyalty value
function get_plot_loyalty($l,$LY){

	$L_month = $l;
	$L_year =  date('Y') - $LY;
	
	$loyalty_data = "SELECT SUM(`loyalty_earned`) AS trans FROM member_transactions WHERE `month` = '$L_month' AND `year` = '$L_year'"; 
	$L_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$loyalty_data));
	$Loyalty = $L_query['trans'];
	return $Loyalty;
	
}

	//Checks for points and available
	if(empty($_POST['verify']) === false){
		$TheID = $_POST['verify'];
		//Gets clients email
		$get_mail = "SELECT `email_address` AS mail FROM `member_contact` WHERE `member_id` = '$TheID'"; 
		$mail_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$get_mail));
		$the_mail = $mail_query['mail'];
	
		//Get Available
	$get_Points = "SELECT `total_points_available` AS points, `company_name` AS company, `redemptions_count` AS count, `total_points_redeemed` AS red FROM `member_loyalty_funds` WHERE `member_id` = '$TheID'"; 
	$points_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$get_Points));
	$points_result = $points_query['points']; 
	$Company_name = trim($points_query['company']);
	$Redeemptions_performed = $points_query['count'];
	$Redeemed_points = $points_query['red'];
	$Available_Points = $points_result;;
	
	$_SESSION['Available']= $Available_Points;
	$_SESSION['member']= $TheID;
	$_SESSION['company']= $Company_name;
	$_SESSION['red_count']= $Redeemptions_performed;
	$_SESSION['red_points']= $Redeemed_points;
	$_SESSION['red_mail']= $the_mail;
			//Redirect User to home
	
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

    <title>Home | </title>

    <!-- Bootstrap -->
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
 
    <!-- Custom Theme Style -->
    <link href="../query/dist/css/custom.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body dark">
      <div class="main_container">
	  
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="#" class="site_title"><i class="fa fa-anchor"></i> <span>Jade Collections</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <img src="../images/user.png" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $username; ?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

			            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
				  <!--End of entries-->
				  <br>
				  <br>
<?php
switch($profile){
case 'MANAGEMENT':	
echo '

				  <li><a><i class="fa fa-home"></i>Home<span class="fa fa-chevron-down"></span></a>
				  <ul class="nav child_menu">
                      <li><a href="../dash">All Branches</a></li>
                      <li><a>Kenyatta Avenue</a></li>
                      <li><a href="#">Tom Mboya Str</a></li>
                      <li><a href="#">Haile Sellasie Av</a></li>
                      <li><a href="#">WestLands</a></li>
                    </ul> 
				  </li>
				  <li><a><i class="fa fa-group"></i> Users <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="add_user.php">Invite new user</a></li>
                      <li><a href="stats_use.php">View Users Stats</a></li>
                      <li><a href="reg/">Register Customers</a></li>
                      <li><a href="stats_guests.php">View Customer</a></li>
                    </ul> 
                  </li>   				  
				  <li><a href="red_io.php"><i class="fa fa-trophy"></i> Customer Redemption</a>
                  </li>	
				  <li><a><i class="fa fa-edit"></i> Receipts <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="tbs/receipts.php"> Program Receipts</a></li>
                    </ul> 
                  </li>
				  
				  
			  <li><a><i class="fa fa-bell-o"></i>Engagement Platforms <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="eng/mailp.php">Email Platform</a></li>
                    </ul> 
                  </li>
				  
				  <li><a> <i class="fa fa-table"></i>Members Tables<span class="fa fa-chevron-down"></span></a>
				   <ul class="nav child_menu">
                      <li><a href="tbs/contacts.php?mode=contacts">Members Contacts</a></li>
                      <li><a href="tbs/loyalty.php?mode=loyalty">Loyalty Funds</a></li>
                      <li><a href="tbs/redemptions.php?mode=request">Redemption</a></li>
                      <li><a href="tbs/transactions.php?mode=transactions">Transaction</a></li>
                    </ul> 
				  </li>	
				  
				  <li><a><i class="fa fa-lightbulb-o"></i> Program Reports <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="rpt/individual_property.php"> Individual Member Report </a></li>
                      <li><a href="rpt/general_report.php"> General Program Report </a></li>
                    </ul> 
                  </li>
				  
				  <li><a href="arc/"><i class="fa fa-archive"></i> Archived Files </a>
				  </li>
				  <li><a href="itspt/"><i class="fa fa-desktop"></i> IT & Support </a>
                  </li>	
				  <li><a  href="itspt/support.php"><i class="fa fa-gear"></i> SYSTEMS SUPPORT </a>
                  </li>	
				  <!--li><a href="http://178.62.100.247/interpel/interpelrewards/production/T&C/INTERPEL%20REWARDS%20PROGRAM%20TERMS%20AND%20CONDITIONS.pdf"><i class="fa fa-file-pdf-o"></i> Terms & Conditions</a>
                  </li--> 
				  

';
break;

case 'MARKETING';
echo '
				  <li><a href="reg/"><i class="fa fa-group"></i> Register Member</a>
                  </li>   
				  <li><a href="tbs/receipts.php"><i class="fa fa-edit"></i> Reward Receipts </a>
                  </li>
				  <li><a> <i class="fa fa-table"></i>Members Tables<span class="fa fa-chevron-down"></span></a>
				   <ul class="nav child_menu">
                      <li><a href="tbs/contacts.php?mode=contacts">Members Contacts</a></li>
                      <li><a href="tbs/loyalty.php?mode=loyalty">Loyalty Funds</a></li>
                      <li><a href="tbs/redemptions.php?mode=request">Redemption</a></li>
                      <li><a href="tbs/transactions.php?mode=transactions">Transaction</a></li>
                    </ul> 
				  </li>	 
				  <li><a href="stats_guests.php"><i class="fa fa-eye"></i> View Guests </a>
                  </li>
				  
			  <li><a><i class="fa fa-bell-o"></i>Engagement Platforms <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="eng/mailp.php">Email Platform</a></li>
                    </ul> 
                  </li>
				  
				  <li><a href="arc/"><i class="fa fa-archive"></i> Archived Files </a></li>
				  
				  <li><a><i class="fa fa-lightbulb-o"></i> Program Reports <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="rpt/individual_property.php"> Individual Member Report </a></li>
                      <li><a href="rpt/general_report.php"> General Program Report </a></li>
                    </ul> 
                  </li>
				  	
                  </li>	
				  <!--li><a href="http://178.62.100.247/interpel/interpelrewards/production/T&C/INTERPEL%20REWARDS%20PROGRAM%20TERMS%20AND%20CONDITIONS.pdf"><i class="fa fa-file-pdf-o"></i> Terms & Conditions</a>
                  </li--> 
				  

';
break;

case 'ACCOUNTING';
echo '
				  <li><a href="stats_guests.php"><i class="fa fa-eye"></i> Users </a>
                  </li>	
				  
				  <li><a href="reg/"><i class="fa fa-group"></i> Register Guest </a>
                  </li>
				  
				  <li><a href="red_io.php"><i class="fa fa-trophy"></i> Reward Guest </a>
                  </li>					  

				  <li><a href="arc/"><i class="fa fa-archive"></i> Archived Files </a></li>
';
break;
case 'IT';
echo '
				  <li><a href="itspt/index.php"><i class="fa fa-cloud-upload"></i> FILES UPLOAD </a>
                    
                  </li>	
				  <li><a  href="itspt/support.php"><i class="fa fa-gear"></i> SYSTEMS SUPPORT </a>
                  </li>	
';
break;
};
?>				
					<!--End of entries-->				  
                </ul>
              </div>			  
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a href="../query/php_files/core/logout.php" data-toggle="tooltip" data-placement="top" title="Logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
			
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav class="" role="navigation">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="row top_tiles" >
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats"   style="text-align: center;">
                  <div class="count"><small><?php echo $purchase; ?></small></div>
				  <h3><strong><i class="fa fa-pagelines"></i></strong></h3>
                  <h3>Total Purchase Value</h3>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats"   style="text-align: center;">             
					<div class="count"><small><?php echo $loyalty; ?></small></div>
				  <h3><strong><i class="fa fa-pagelines"></i></strong></h3>
                  <h3>Total Tokens Earned</h3>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats"   style="text-align: center;">
                    <div class="count"><small><?php echo $expired; ?></small></div>
				  <h3><strong><i class="fa fa-pagelines"></i></strong></h3>
                  <h3>Total Expired Tokens</h3>
                </div>
              </div>			  
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats"   style="text-align: center;">
                     <div class="count"><small><?php echo $redeem; ?></small></div>
				  <h3><strong><i class="fa fa-pagelines"></i></strong></h3>
                  <h3>Total Redeemed Points</h3>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Transaction Summary <small>monthly progress</small></h2>
                    <div class="filter">
                      <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>December 30, 2017 - December 28, 2018</span> <b class="caret"></b>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="demo-container" style="height:380px">
                        <div id="placeholder33x" class="demo-placeholder" style="height:370px"></div><!-- This line houses the graph-->
                      </div>
                    </div>

 					  <!-- The Redemption Panel Goes Here-->
					  
					  <!-- The Redemption Panel ends Here-->
 			  
                    </div>
                  </div>
                </div>
              </div>
            </div>
      
	  <!-- Active Profiles -->   
		<div class="row">
              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Active Profiles <small>Based on Transactions and Redemptions</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day">1</p>
                      </a>
                      <div class="media-body">
                               <a class="" href="#"><?php echo $first_name; ?></a>
                              <p><strong>Ksh <?php echo number_format($first_amount); ?> </strong> Total Redeemed </p>
                              <p> <small><?php echo $F_pur; ?> Purchases  </small><strong><i class="fa fa-pagelines"></i></strong><small>  <?php echo $F_count; ?> Redemptions</small></p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day">2</p>
                      </a>
                      <div class="media-body">
                              <a class="" href="#"><?php echo $sec_name; ?></a>
                              <p><strong>Ksh <?php echo number_format($sec_amount); ?> </strong> Total Amount Spent </p>
                              <p> <small><?php echo $S_pur; ?> Purchases </small><strong><i class="fa fa-pagelines"></i></strong><small>  <?php echo $S_count; ?> Redemptions </small></p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day">3</p>
                      </a>
                      <div class="media-body">
                              <a class="" href="#"><?php echo $third_name; ?></a>
                              <p><strong>Ksh <?php echo number_format($third_amount); ?> </strong> Total Amount Spent </p>
                              <p> <small><?php echo $T_pur; ?> Purchases</small><strong><i class="fa fa-pagelines"></i></strong><small>  <?php echo $T_count; ?> Redemptions </small></p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day">4</p>
                      </a>
                      <div class="media-body">
                              <a class="" href="#"><?php echo $forth_name; ?></a>
                              <p><strong>Ksh <?php echo number_format($forth_amount); ?>. </strong> Total Amount Spent </p>
                              <p> <small><?php echo $FO_pur; ?> Purchases </small><strong><i class="fa fa-pagelines"></i></strong><small>  <?php echo $FO_count; ?> Redemptions </small></p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day">5</p>
                      </a>
                      <div class="media-body">
                              <a class="" href="#"><?php echo $fifth_name; ?></a>
                              <p><strong>Ksh <?php echo number_format($fifth_amount); ?>. </strong> Total Amount Spent </p>
                              <p> <small><?php echo $FI_pur; ?> Purchases </small><strong><i class="fa fa-pagelines"></i></strong><small>  <?php echo $FI_count; ?> Redemptions </small></p>
                      </div>
                    </article>
                  </div>
                </div>
              </div>

			   <!-- Top Profiles --> 
              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Top Profiles<small>Total Tokens Value</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day">1</p>
                      </a>
                      <div class="media-body">
                                <P><a class="" href="#"><?php echo $first_points_name; ?></a></p> 
                              <p><strong> <?php echo $first_points_id; ?>  </strong><i class="fa fa-pagelines"></i><strong>  Ksh <?php echo $first_points_amount; ?></strong></p>
                              <p> <small> <?php echo $F_points_count; ?>  Transactions</small>
                              </p>
                      </div>
                    </article>
                       <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day">2</p>
                      </a>
                       <div class="media-body">
                                <P><a class="" href="#"><?php echo $sec_points_name; ?></a></p> 
                              <p><strong> <?php echo $sec_points_id; ?>  </strong><i class="fa fa-pagelines"></i><strong>  Ksh <?php echo $sec_points_amount; ?></strong></p>
                              <p> <small> <?php echo $S_points_count; ?>  Transactions</small>
                              </p>
                      </div>
                    </article>
                       <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day">3</p>
                      </a>
                      <div class="media-body">
                                <P><a class="" href="#"><?php echo $third_points_name; ?></a></p> 
                              <p><strong> <?php echo $third_points_id; ?>  </strong><i class="fa fa-pagelines"></i><strong>  Ksh <?php echo $third_points_amount; ?></strong></p>
                              <p> <small> <?php echo $T_points_count; ?>  Transactions</small>
                              </p>
                      </div>
                    </article>
						<article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day">4</p>
                      </a>
                       <div class="media-body">
                                <P><a class="" href="#"><?php echo $forth_points_name; ?></a></p> 
                              <p><strong> <?php echo $forth_points_id; ?>  </strong><i class="fa fa-pagelines"></i><strong>  Ksh <?php echo $forth_points_amount; ?></strong></p>
                              <p> <small> <?php echo $FO_points_count; ?>  Transactions</small>
                              </p>
                      </div>
                    </article>
						<article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day">5</p>
                      </a>
                      <div class="media-body">
                                <P><a class="" href="#"><?php echo $fifth_points_name; ?></a></p> 
                              <p><strong> <?php echo $fifth_points_id; ?>  </strong><i class="fa fa-pagelines"></i><strong>  Ksh <?php echo $fifth_points_amount; ?></strong></p>
                              <p> <small> <?php echo $FI_points_count; ?>  Transactions</small>
                              </p>
                      </div>
                    </article>
                  </div>
                </div>
              </div>

			   <!-- Bottom Profiles --> 
              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Bottom Profiles <small>Tokens Value</small></h2>
                   
                    <div class="clearfix"></div>
                  </div>
                    <div class="x_content">
                        <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day"><?php echo 0//$total_c_count; ?></p>
                      </a>
                      <div class="media-body">
                                <p><a class="title" href="#">  <?php echo $last_name; ?>  </a></p> 
                              <p><strong> <?php echo $last_id; ?> </strong> <i class="fa fa-anchor"></i><strong> Ksh <?php echo $last_amount; ?>. </strong></p>
                              <p> <small><?php echo $L_points_count; ?> Transactions Count</small>
                              </p>
                      </div>
                    </article>
                        <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day"><?php echo 0//$total_c_count - 1; ?></p>
                      </a>
                      <div class="media-body">
                                <P><a class="title" href="#"> <?php echo $sec_last_name; ?></a></p> 
                              <p> <strong> <?php echo $sec_last_id; ?></strong> <i class="fa fa-anchor"></i> <strong> Ksh  <?php echo $sec_last_amount; ?> </strong></p>
                              <p> <small><?php echo $sec_last_points_count; ?> Transactions Count</small>
                              </p>
                      </div>
                    </article>
                        <article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day"><?php echo 0//$total_c_count - 2; ?></p>
                      </a>
                      <div class="media-body">
                                <p><a class="title" href="#"> <?php echo $third_last_name; ?> </a></p> 
								<p><strong> <?php echo $third_last_id; ?> </strong><i class="fa fa-anchor"></i><strong> Ksh  <?php echo $third_last_amount; ?>. </strong></p>
                              <p> <small><?php echo $third_last_points_count; ?> Transactions Count</small>
                              </p>
                      </div>
                    </article>
						<article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day"><?php echo 0//$total_c_count - 3; ?></p>
                      </a>
                      <div class="media-body">
                                <p><a class="title" href="#"> <?php echo $forth_last_name; ?></a></p> 
                              <p><strong> <?php echo $forth_last_id; ?> </strong> <i class="fa fa-anchor"></i> <strong> Ksh  <?php echo $forth_last_amount; ?> </strong></p>
                              <p> <small><?php echo $forth_last_points_count; ?> Transactions Count</small>
                              </p>
                      </div>
                    </article>
						<article class="media event">
                      <a class="pull-left date">
                        <p class="month">Pos</p>
                        <p class="day"><?php echo 0//$total_c_count - 4; ?></p>
                      </a>
                      <div class="media-body">
                                <p><a class="title" href="#"> <?php echo $fifth_last_name; ?></a></p> 
                              <p><strong>  <?php echo $fifth_last_id; ?></strong> <i class="fa fa-anchor"></i><strong> Ksh  <?php echo $fifth_last_amount; ?>. </strong></p>
                              <p> <small><?php echo $fifth_last_points_count; ?> Transactions Count</small>
                              </p>
                      </div>
                    </article>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            <a href="https://www.indulgencemarketing.co.ke">Indulgence Marketing <?php echo date('Y');?> </a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../../vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="../../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- jQuery Sparklines -->
    <script src="../../vendors/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <!-- Flot -->
    <script src="../../vendors/Flot/jquery.flot.js"></script>
    <script src="../../vendors/Flot/jquery.flot.pie.js"></script>
    <script src="../../vendors/Flot/jquery.flot.categories.js"></script>
    <script src="../../vendors/Flot/jquery.flot.stack.js"></script>
    <script src="../../vendors/Flot/jquery.flot.resize.js"></script>
    
    <!-- Custom Theme Scripts -->
    <script src="../query/dist/js/custom.min.js"></script>

    <!-- Flot -->
    <script>
      $(document).ready(function() {
        //define chart clolors ( you maybe add more colors if you want or flot will add it automatic )
        var chartColours = ['#96CA59', '#3F97EB', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'];
		var d1 = [ ];
		var d2 = [ ];
		
			var d = new Date();
			
			
			var mon = d.getMonth();
			var m = d.getFullYear();
			
			var n = m - 1;
			
		//generate random number for charts
		
				if(mon == 10){
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					d1.push(['Feb ' + m, "<?php echo get_plot_data(2,0);?>"]);
					d2.push(['Feb ' + m, "<?php echo get_plot_loyalty(2,0);?>" * 10]);
					d1.push(['Mar ' + m, "<?php echo get_plot_data(3,0);?>"]);
					d2.push(['Mar ' + m, "<?php echo get_plot_loyalty(3,0);?>" * 10]);
					d1.push(['Apr ' + m, "<?php echo get_plot_data(4,0);?>"]);
					d2.push(['Apr ' + m, "<?php echo get_plot_loyalty(4,0);?>" * 10]);
					d1.push(['May ' + m, "<?php echo get_plot_data(5,0);?>"]);
					d2.push(['May ' + m, "<?php echo get_plot_loyalty(5,0);?>" * 10]);
					d1.push(['Jun ' + m, "<?php echo get_plot_data(6,0);?>"]);
					d2.push(['Jun ' + m, "<?php echo get_plot_loyalty(6,0);?>" * 10]);
					d1.push(['Jul ' + m, "<?php echo get_plot_data(7,0);?>"]);
					d2.push(['Jul ' + m, "<?php echo get_plot_loyalty(7,0);?>" * 10]);
					d1.push(['Aug ' + m, "<?php echo get_plot_data(8,0);?>"]);
					d2.push(['Aug ' + m, "<?php echo get_plot_loyalty(8,0);?>" * 10]);
					d1.push(['Sep ' + m, "<?php echo get_plot_data(9,0);?>"]);
					d2.push(['Sep ' + m, "<?php echo get_plot_loyalty(9,0);?>" * 10]);
					d1.push(['Oct ' + m, "<?php echo get_plot_data(10,0);?>"]);
					d2.push(['Oct ' + m, "<?php echo get_plot_loyalty(10,0);?>" * 10]);
					d1.push(['Nov ' + m, "<?php echo get_plot_data(11,0);?>"]);
					d2.push(['Nov ' + m, "<?php echo get_plot_loyalty(11,0);?>" * 10]);
					
				}else
					if(mon == 9){
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					d1.push(['Feb ' + m, "<?php echo get_plot_data(2,0);?>"]);
					d2.push(['Feb ' + m, "<?php echo get_plot_loyalty(2,0);?>" * 10]);
					d1.push(['Mar ' + m, "<?php echo get_plot_data(3,0);?>"]);
					d2.push(['Mar ' + m, "<?php echo get_plot_loyalty(3,0);?>" * 10]);
					d1.push(['Apr ' + m, "<?php echo get_plot_data(4,0);?>"]);
					d2.push(['Apr ' + m, "<?php echo get_plot_loyalty(4,0);?>" * 10]);
					d1.push(['May ' + m, "<?php echo get_plot_data(5,0);?>"]);
					d2.push(['May ' + m, "<?php echo get_plot_loyalty(5,0);?>" * 10]);
					d1.push(['Jun ' + m, "<?php echo get_plot_data(6,0);?>"]);
					d2.push(['Jun ' + m, "<?php echo get_plot_loyalty(6,0);?>" * 10]);
					d1.push(['Jul ' + m, "<?php echo get_plot_data(7,0);?>"]);
					d2.push(['Jul ' + m, "<?php echo get_plot_loyalty(7,0);?>" * 10]);
					d1.push(['Aug ' + m, "<?php echo get_plot_data(8,0);?>"]);
					d2.push(['Aug ' + m, "<?php echo get_plot_loyalty(8,0);?>" * 10]);
					d1.push(['Sep ' + m, "<?php echo get_plot_data(9,0);?>"]);
					d2.push(['Sep ' + m, "<?php echo get_plot_loyalty(9,0);?>" * 10]);
					d1.push(['Oct ' + m, "<?php echo get_plot_data(10,0);?>"]);
					d2.push(['Oct ' + m, "<?php echo get_plot_loyalty(10,0);?>" * 10]);
				}else if(mon == 8){
					d1.push(['Nov ' + n, "<?php echo get_plot_data(11,1);?>"]);
					d2.push(['Nov ' + n, "<?php echo get_plot_loyalty(11,1);?>" * 10]);
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					d1.push(['Feb ' + m, "<?php echo get_plot_data(2,0);?>"]);
					d2.push(['Feb ' + m, "<?php echo get_plot_loyalty(2,0);?>" * 10]);
					d1.push(['Mar ' + m, "<?php echo get_plot_data(3,0);?>"]);
					d2.push(['Mar ' + m, "<?php echo get_plot_loyalty(3,0);?>" * 10]);
					d1.push(['Apr ' + m, "<?php echo get_plot_data(4,0);?>"]);
					d2.push(['Apr ' + m, "<?php echo get_plot_loyalty(4,0);?>" * 10]);
					d1.push(['May ' + m, "<?php echo get_plot_data(5,0);?>"]);
					d2.push(['May ' + m, "<?php echo get_plot_loyalty(5,0);?>" * 10]);
					d1.push(['Jun ' + m, "<?php echo get_plot_data(6,0);?>"]);
					d2.push(['Jun ' + m, "<?php echo get_plot_loyalty(6,0);?>" * 10]);
					d1.push(['Jul ' + m, "<?php echo get_plot_data(7,0);?>"]);
					d2.push(['Jul ' + m, "<?php echo get_plot_loyalty(7,0);?>" * 10]);
					d1.push(['Aug ' + m, "<?php echo get_plot_data(8,0);?>"]);
					d2.push(['Aug ' + m, "<?php echo get_plot_loyalty(8,0);?>" * 10]);
					d1.push(['Sep ' + m, "<?php echo get_plot_data(9,0);?>"]);
					d2.push(['Sep ' + m, "<?php echo get_plot_loyalty(9,0);?>" * 10]);
				}else if(mon == 7){
					d1.push(['Oct ' + n, "<?php echo get_plot_data(10,1);?>"]);
					d2.push(['Oct ' + n, "<?php echo get_plot_loyalty(10,1);?>" * 10]);
					d1.push(['Nov ' + n, "<?php echo get_plot_data(11,1);?>"]);
					d2.push(['Nov ' + n, "<?php echo get_plot_loyalty(11,1);?>" * 10]);
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					d1.push(['Feb ' + m, "<?php echo get_plot_data(2,0);?>"]);
					d2.push(['Feb ' + m, "<?php echo get_plot_loyalty(2,0);?>" * 10]);
					d1.push(['Mar ' + m, "<?php echo get_plot_data(3,0);?>"]);
					d2.push(['Mar ' + m, "<?php echo get_plot_loyalty(3,0);?>" * 10]);
					d1.push(['Apr ' + m, "<?php echo get_plot_data(4,0);?>"]);
					d2.push(['Apr ' + m, "<?php echo get_plot_loyalty(4,0);?>" * 10]);
					d1.push(['May ' + m, "<?php echo get_plot_data(5,0);?>"]);
					d2.push(['May ' + m, "<?php echo get_plot_loyalty(5,0);?>" * 10]);
					d1.push(['Jun ' + m, "<?php echo get_plot_data(6,0);?>"]);
					d2.push(['Jun ' + m, "<?php echo get_plot_loyalty(6,0);?>" * 10]);
					d1.push(['Jul ' + m, "<?php echo get_plot_data(7,0);?>"]);
					d2.push(['Jul ' + m, "<?php echo get_plot_loyalty(7,0);?>" * 10]);
					d1.push(['Aug ' + m, "<?php echo get_plot_data(8,0);?>"]);
					d2.push(['Aug ' + m, "<?php echo get_plot_loyalty(8,0);?>" * 10]);
				}else if(mon == 6){
					d1.push(['Sep ' + n, "<?php echo get_plot_data(9,1);?>"]);
					d2.push(['Sep ' + n, "<?php echo get_plot_loyalty(9,1);?>" * 10]);
					d1.push(['Oct ' + n, "<?php echo get_plot_data(10,1);?>"]);
					d2.push(['Oct ' + n, "<?php echo get_plot_loyalty(10,1);?>" * 10]);
					d1.push(['Nov ' + n, "<?php echo get_plot_data(11,1);?>"]);
					d2.push(['Nov ' + n, "<?php echo get_plot_loyalty(11,1);?>" * 10]);
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					d1.push(['Feb ' + m, "<?php echo get_plot_data(2,0);?>"]);
					d2.push(['Feb ' + m, "<?php echo get_plot_loyalty(2,0);?>" * 10]);
					d1.push(['Mar ' + m, "<?php echo get_plot_data(3,0);?>"]);
					d2.push(['Mar ' + m, "<?php echo get_plot_loyalty(3,0);?>" * 10]);
					d1.push(['Apr ' + m, "<?php echo get_plot_data(4,0);?>"]);
					d2.push(['Apr ' + m, "<?php echo get_plot_loyalty(4,0);?>" * 10]);
					d1.push(['May ' + m, "<?php echo get_plot_data(5,0);?>"]);
					d2.push(['May ' + m, "<?php echo get_plot_loyalty(5,0);?>" * 10]);
					d1.push(['Jun ' + m, "<?php echo get_plot_data(6,0);?>"]);
					d2.push(['Jun ' + m, "<?php echo get_plot_loyalty(6,0);?>" * 10]);
					d1.push(['Jul ' + m, "<?php echo get_plot_data(7,0);?>"]);
					d2.push(['Jul ' + m, "<?php echo get_plot_loyalty(7,0);?>" * 10]);
				}else if(mon == 5){
					d1.push(['Aug ' + n, "<?php echo get_plot_data(8,1);?>"]);
					d2.push(['Aug ' + n, "<?php echo get_plot_loyalty(8,1);?>" * 10]);
					d1.push(['Sep ' + n, "<?php echo get_plot_data(9,1);?>"]);
					d2.push(['Sep ' + n, "<?php echo get_plot_loyalty(9,1);?>" * 10]);
					d1.push(['Oct ' + n, "<?php echo get_plot_data(10,1);?>"]);
					d2.push(['Oct ' + n, "<?php echo get_plot_loyalty(10,1);?>" * 10]);
					d1.push(['Nov ' + n, "<?php echo get_plot_data(11,1);?>"]);
					d2.push(['Nov ' + n, "<?php echo get_plot_loyalty(11,1);?>" * 10]);
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					d1.push(['Feb ' + m, "<?php echo get_plot_data(2,0);?>"]);
					d2.push(['Feb ' + m, "<?php echo get_plot_loyalty(2,0);?>" * 10]);
					d1.push(['Mar ' + m, "<?php echo get_plot_data(3,0);?>"]);
					d2.push(['Mar ' + m, "<?php echo get_plot_loyalty(3,0);?>" * 10]);
					d1.push(['Apr ' + m, "<?php echo get_plot_data(4,0);?>"]);
					d2.push(['Apr ' + m, "<?php echo get_plot_loyalty(4,0);?>" * 10]);
					d1.push(['May ' + m, "<?php echo get_plot_data(5,0);?>"]);
					d2.push(['May ' + m, "<?php echo get_plot_loyalty(5,0);?>" * 10]);
					d1.push(['Jun ' + m, "<?php echo get_plot_data(6,0);?>"]);
					d2.push(['Jun ' + m, "<?php echo get_plot_loyalty(6,0);?>" * 10]);
				}else if(mon == 4){
					d1.push(['Jul ' + n, "<?php echo get_plot_data(7,1);?>"]);
					d2.push(['Jul ' + n, "<?php echo get_plot_loyalty(7,1);?>" * 10]);
					d1.push(['Aug ' + n, "<?php echo get_plot_data(8,1);?>"]);
					d2.push(['Aug ' + n, "<?php echo get_plot_loyalty(8,1);?>" * 10]);
					d1.push(['Sep ' + n, "<?php echo get_plot_data(9,1);?>"]);
					d2.push(['Sep ' + n, "<?php echo get_plot_loyalty(9,1);?>" * 10]);
					d1.push(['Oct ' + n, "<?php echo get_plot_data(10,1);?>"]);
					d2.push(['Oct ' + n, "<?php echo get_plot_loyalty(10,1);?>" * 10]);
					d1.push(['Nov ' + n, "<?php echo get_plot_data(11,1);?>"]);
					d2.push(['Nov ' + n, "<?php echo get_plot_loyalty(11,1);?>" * 10]);
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					d1.push(['Feb ' + m, "<?php echo get_plot_data(2,0);?>"]);
					d2.push(['Feb ' + m, "<?php echo get_plot_loyalty(2,0);?>" * 10]);
					d1.push(['Mar ' + m, "<?php echo get_plot_data(3,0);?>"]);
					d2.push(['Mar ' + m, "<?php echo get_plot_loyalty(3,0);?>" * 10]);
					d1.push(['Apr ' + m, "<?php echo get_plot_data(4,0);?>"]);
					d2.push(['Apr ' + m, "<?php echo get_plot_loyalty(4,0);?>" * 10]);
					d1.push(['May ' + m, "<?php echo get_plot_data(5,0);?>"]);
					d2.push(['May ' + m, "<?php echo get_plot_loyalty(5,0);?>" * 10]);
				}else if(mon == 3){
					d1.push(['Jun ' + n, "<?php echo get_plot_data(6,1);?>"]);
					d2.push(['Jun ' + n, "<?php echo get_plot_loyalty(6,1);?>" * 10]);
					d1.push(['Jul ' + n, "<?php echo get_plot_data(7,1);?>"]);
					d2.push(['Jul ' + n, "<?php echo get_plot_loyalty(7,1);?>" * 10]);
					d1.push(['Aug ' + n, "<?php echo get_plot_data(8,1);?>"]);
					d2.push(['Aug ' + n, "<?php echo get_plot_loyalty(8,1);?>" * 10]);
					d1.push(['Sep ' + n, "<?php echo get_plot_data(9,1);?>"]);
					d2.push(['Sep ' + n, "<?php echo get_plot_loyalty(9,1);?>" * 10]);
					d1.push(['Oct ' + n, "<?php echo get_plot_data(10,1);?>"]);
					d2.push(['Oct ' + n, "<?php echo get_plot_loyalty(10,1);?>" * 10]);
					d1.push(['Nov ' + n, "<?php echo get_plot_data(11,1);?>"]);
					d2.push(['Nov ' + n, "<?php echo get_plot_loyalty(11,1);?>" * 10]);
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					d1.push(['Feb ' + m, "<?php echo get_plot_data(2,0);?>"]);
					d2.push(['Feb ' + m, "<?php echo get_plot_loyalty(2,0);?>" * 10]);
					d1.push(['Mar ' + m, "<?php echo get_plot_data(3,0);?>"]);
					d2.push(['Mar ' + m, "<?php echo get_plot_loyalty(3,0);?>" * 10]);
					d1.push(['Apr ' + m, "<?php echo get_plot_data(4,0);?>"]);
					d2.push(['Apr ' + m, "<?php echo get_plot_loyalty(4,0);?>" * 10]);
				}else if(mon == 2){
					d1.push(['May ' + n, "<?php echo get_plot_data(5,1);?>"]);
					d2.push(['May ' + n, "<?php echo get_plot_loyalty(5,1);?>" * 10]);
					d1.push(['Jun ' + n, "<?php echo get_plot_data(6,1);?>"]);
					d2.push(['Jun ' + n, "<?php echo get_plot_loyalty(6,1);?>" * 10]);
					d1.push(['Jul ' + n, "<?php echo get_plot_data(7,1);?>"]);
					d2.push(['Jul ' + n, "<?php echo get_plot_loyalty(7,1);?>" * 10]);
					d1.push(['Aug ' + n, "<?php echo get_plot_data(8,1);?>"]);
					d2.push(['Aug ' + n, "<?php echo get_plot_loyalty(8,1);?>" * 10]);
					d1.push(['Sep ' + n, "<?php echo get_plot_data(9,1);?>"]);
					d2.push(['Sep ' + n, "<?php echo get_plot_loyalty(9,1);?>" * 10]);
					d1.push(['Oct ' + n, "<?php echo get_plot_data(10,1);?>"]);
					d2.push(['Oct ' + n, "<?php echo get_plot_loyalty(10,1);?>" * 10]);
					d1.push(['Nov ' + n, "<?php echo get_plot_data(11,1);?>"]);
					d2.push(['Nov ' + n, "<?php echo get_plot_loyalty(11,1);?>" * 10]);
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					d1.push(['Feb ' + m, "<?php echo get_plot_data(2,0);?>"]);
					d2.push(['Feb ' + m, "<?php echo get_plot_loyalty(2,0);?>" * 10]);
					d1.push(['Mar ' + m, "<?php echo get_plot_data(3,0);?>"]);
					d2.push(['Mar ' + m, "<?php echo get_plot_loyalty(3,0);?>" * 10]);
				}else if(mon == 1){
					
					d1.push(['Apr ' + n, "<?php echo get_plot_data(4,1);?>"]);
					d2.push(['Apr ' + n, "<?php echo get_plot_loyalty(4,1);?>" * 10]);
					d1.push(['May ' + n, "<?php echo get_plot_data(5,1);?>"]);
					d2.push(['May ' + n, "<?php echo get_plot_loyalty(5,1);?>" * 10]);
					d1.push(['Jun ' + n, "<?php echo get_plot_data(6,1);?>"]);
					d2.push(['Jun ' + n, "<?php echo get_plot_loyalty(6,1);?>" * 10]);
					d1.push(['Jul ' + n, "<?php echo get_plot_data(7,1);?>"]);
					d2.push(['Jul ' + n, "<?php echo get_plot_loyalty(7,1);?>" * 10]);
					d1.push(['Aug ' + n, "<?php echo get_plot_data(8,1);?>"]);
					d2.push(['Aug ' + n, "<?php echo get_plot_loyalty(8,1);?>" * 10]);
					d1.push(['Sep ' + n, "<?php echo get_plot_data(9,1);?>"]);
					d2.push(['Sep ' + n, "<?php echo get_plot_loyalty(9,1);?>" * 10]);
					d1.push(['Oct ' + n, "<?php echo get_plot_data(10,1);?>"]);
					d2.push(['Oct ' + n, "<?php echo get_plot_loyalty(10,1);?>" * 10]);
					d1.push(['Nov ' + n, "<?php echo get_plot_data(11,1);?>"]);
					d2.push(['Nov ' + n, "<?php echo get_plot_loyalty(11,1);?>" * 10]);
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					d1.push(['Feb ' + m, "<?php echo get_plot_data(2,0);?>"]);
					d2.push(['Feb ' + m, "<?php echo get_plot_loyalty(2,0);?>" * 10]);
					
				}else if(mon == 0){
					
					d1.push(['Mar ' + n, "<?php echo get_plot_data(3,1);?>"]);
					d2.push(['Mar ' + n, "<?php echo get_plot_loyalty(3,1);?>" * 10]);
					d1.push(['Apr ' + n, "<?php echo get_plot_data(4,1);?>"]);
					d2.push(['Apr ' + n, "<?php echo get_plot_loyalty(4,1);?>" * 10]);
					d1.push(['May ' + n, "<?php echo get_plot_data(5,1);?>"]);
					d2.push(['May ' + n, "<?php echo get_plot_loyalty(5,1);?>" * 10]);
					d1.push(['Jun ' + n, "<?php echo get_plot_data(6,1);?>"]);
					d2.push(['Jun ' + n, "<?php echo get_plot_loyalty(6,1);?>" * 10]);
					d1.push(['Jul ' + n, "<?php echo get_plot_data(7,1);?>"]);
					d2.push(['Jul ' + n, "<?php echo get_plot_loyalty(7,1);?>" * 10]);
					d1.push(['Aug ' + n, "<?php echo get_plot_data(8,1);?>"]);
					d2.push(['Aug ' + n, "<?php echo get_plot_loyalty(8,1);?>" * 10]);
					d1.push(['Sep ' + n, "<?php echo get_plot_data(9,1);?>"]);
					d2.push(['Sep ' + n, "<?php echo get_plot_loyalty(9,1);?>" * 10]);
					d1.push(['Oct ' + n, "<?php echo get_plot_data(10,1);?>"]);
					d2.push(['Oct ' + n, "<?php echo get_plot_loyalty(10,1);?>" * 10]);
					d1.push(['Nov ' + n, "<?php echo get_plot_data(11,1);?>"]);
					d2.push(['Nov ' + n, "<?php echo get_plot_loyalty(11,1);?>" * 10]);
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					d1.push(['Jan ' + m, "<?php echo get_plot_data(1,0);?>"]);
					d2.push(['Jan ' + m, "<?php echo get_plot_loyalty(1,0);?>" * 10]);
					
				}else if(mon == 11){
					d1.push(['Feb ' + n, "<?php echo get_plot_data(2,1);?>"]);
					d2.push(['Feb ' + n, "<?php echo get_plot_loyalty(2,1);?>" * 10]);
					d1.push(['Mar ' + n, "<?php echo get_plot_data(3,1);?>"]);
					d2.push(['Mar ' + n, "<?php echo get_plot_loyalty(3,1);?>" * 10]);
					d1.push(['Apr ' + n, "<?php echo get_plot_data(4,1);?>"]);
					d2.push(['Apr ' + n, "<?php echo get_plot_loyalty(4,1);?>" * 10]);
					d1.push(['May ' + n, "<?php echo get_plot_data(5,1);?>"]);
					d2.push(['May ' + n, "<?php echo get_plot_loyalty(5,1);?>" * 10]);
					d1.push(['Jun ' + n, "<?php echo get_plot_data(6,1);?>"]);
					d2.push(['Jun ' + n, "<?php echo get_plot_loyalty(6,1);?>" * 10]);
					d1.push(['Jul ' + n, "<?php echo get_plot_data(7,1);?>"]);
					d2.push(['Jul ' + n, "<?php echo get_plot_loyalty(7,1);?>" * 10]);
					d1.push(['Aug ' + n, "<?php echo get_plot_data(8,1);?>"]);
					d2.push(['Aug ' + n, "<?php echo get_plot_loyalty(8,1);?>" * 10]);
					d1.push(['Sep ' + n, "<?php echo get_plot_data(9,1);?>"]);
					d2.push(['Sep ' + n, "<?php echo get_plot_loyalty(9,1);?>" * 10]);
					d1.push(['Oct ' + n, "<?php echo get_plot_data(10,1);?>"]);
					d2.push(['Oct ' + n, "<?php echo get_plot_loyalty(10,1);?>" * 10]);
					d1.push(['Nov ' + n, "<?php echo get_plot_data(11,1);?>"]);
					d2.push(['Nov ' + n, "<?php echo get_plot_loyalty(11,1);?>" * 10]);
					d1.push(['Dec ' + n, "<?php echo get_plot_data(12,1);?>"]);
					d2.push(['Dec ' + n, "<?php echo get_plot_loyalty(12,1);?>" * 10]);
					
					
				}
				
        //graph options
        var options = {
          grid: {
            show: true,
            aboveData: true,
            color: "#3f3f3f",
            labelMargin: 10,
            axisMargin: 0,
            borderWidth: 0,
            borderColor: null,
            minBorderMargin: 5,
            clickable: true,
            hoverable: true,
            autoHighlight: true,
            mouseActiveRadius: 100
          },
          series: {
            lines: {
              show: true,
              fill: true,
              lineWidth: 2,
              steps: false
            },
            points: {
              show: true,
              radius: 4.5,
              symbol: "circle",
              lineWidth: 3.0
            }
          },
          legend: {
            position: "ne",
            margin: [0, -25],
            noColumns: 0,
            labelBoxBorderColor: null,
            labelFormatter: function(label, series) {
              // just add some space to labes
              return label + '&nbsp;&nbsp;';
            },
            width: 40,
            height: 1
          },
          colors: chartColours,
          shadowSize: 0,
          tooltip: true, //activate tooltip
          tooltipOpts: {
            content: "%s: %y.0",
            xDateFormat: "%d/%m",
            shifts: {
              x: -30,
              y: -50
            },
            defaultTheme: false
          },
          yaxis: {
            min: 0
          },
          xaxis: {
            mode: "categories",
			tickLength: 0
          }
        };
        var plot = $.plot($("#placeholder33x"), [
		{
          label: "Transactions View",
          data: d1,
          lines: {
            fillColor: "rgba(150, 202, 89, 0.12)"
          }, //#96CA59 rgba(150, 202, 89, 0.42)
          points: {
            fillColor: "#fff"
          }
        },{
          label: "Loyalty Allocation (*10)",
          data: d2,
          lines: {
            fillColor: "rgba(150, 202, 89, 0.12)"
          }, //#96CA59 rgba(150, 202, 89, 0.42)
          points: {
            fillColor: "#fff"
          }
        }], options);
      });
    </script>
    <!-- /Flot -->

    <!-- jQuery Sparklines -->
    <script>
      $(document).ready(function() {
        $(".sparkline_one").sparkline([4,5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6, 4, 3, 5, 6,5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6, 4, 3, 5, 6], {
          type: 'bar',
          height: '125',
          barWidth: 15,
          colorMap: {
            '': '#a1a1a1'
          },
          barSpacing: 2,
          barColor: '#26B99A'
        });

        $(".sparkline11").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 6, 2, 4, 3, 4, 5, 4, 5, 4, 3], {
          type: 'line',
          height: '40',
          barWidth: 8,
          colorMap: {
            '7': '#a1a1a1'
          },
          barSpacing: 2,
          barColor: '#26B99A'
        });

        $(".sparkline22").sparkline([2, 4, 3, 4, 7, 5, 4, 3, 5, 6, 2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 6], {
          type: 'bar',
          height: '40',
          width: '200',
          lineColor: '#26B99A',
          fillColor: '#ffffff',
          lineWidth: 3,
          spotColor: '#34495E',
          minSpotColor: '#34495E'
        });
      });
    </script>
    <!-- /jQuery Sparklines -->

    <!-- Doughnut Chart -->
    <script>
      $(document).ready(function() {
        var canvasDoughnut,
            options = {
              legend: false,
              responsive: false
            };

        new Chart(document.getElementById("canvas1i"), {
          type: 'doughnut',
          tooltipFillColor: "rgba(51, 51, 51, 0.55)",
          data: {
            labels: [
              "Tier 1",
              "Tier 2",
              "Tier 3",
              "Tier 4",
              "Tier 5"
            ],
            datasets: [{
              data: [35, 10, 40, 15, 20],
              backgroundColor: [
                "#BDC3C7",
                "#9B59B6",
                "#E74C3C",
                "#26B99A",
                "#3498DB"
              ],
              hoverBackgroundColor: [
                "#CFD4D8",
                "#B370CF",
                "#E95E4F",
                "#36CAAB",
                "#49A9EA"
              ]

            }]
          },
          options: options
        });

        new Chart(document.getElementById("canvas1i2"), {
          type: 'doughnut',
          tooltipFillColor: "rgba(51, 51, 51, 0.55)",
          data: {
            labels: [
               "Tier 1",
              "Tier 2",
              "Tier 3",
              "Tier 4",
              "Tier 5"
            ],
            datasets: [{
              data: [15, 20, 30, 10, 30],
              backgroundColor: [
                "#BDC3C7",
                "#9B59B6",
                "#E74C3C",
                "#26B99A",
                "#3498DB"
              ],
              hoverBackgroundColor: [
                "Tier 1",
              "Tier 2",
              "Tier 3",
              "Tier 4",
              "Tier 5"
              ]

            }]
          },
          options: options
        });

        new Chart(document.getElementById("canvas1i3"), {
          type: 'doughnut',
          tooltipFillColor: "rgba(51, 51, 51, 0.55)",
          data: {
            labels: [
               "Tier 1",
              "Tier 2",
              "Tier 3",
              "Tier 4",
              "Tier 5"
            ],
            datasets: [{
              data: [15, 20, 30, 10, 30],
              backgroundColor: [
                "#BDC3C7",
                "#9B59B6",
                "#E74C3C",
                "#26B99A",
                "#3498DB"
              ],
              hoverBackgroundColor: [
                "#CFD4D8",
                "#B370CF",
                "#E95E4F",
                "#36CAAB",
                "#49A9EA"
              ]

            }]
          },
          options: options
        });
      });
    </script>
    <!-- /Doughnut Chart -->

    <!-- bootstrap-daterangepicker -->
    
    <script type="text/javascript">
      $(document).ready(function() {

        var cb = function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        };

        var optionSet1 = {
          startDate: moment().subtract(29, 'days'),
          endDate: moment(),
          minDate: '01/01/2016',
          maxDate: '12/31/2020',
          dateLimit: {
            days: 60
          },
          showDropdowns: true,
          showWeekNumbers: true,
          timePicker: false,
          timePickerIncrement: 1,
          timePicker12Hour: true,
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          opens: 'left',
          buttonClasses: ['btn btn-default'],
          applyClass: 'btn-small btn-primary',
          cancelClass: 'btn-small',
          format: 'MM/DD/YYYY',
          separator: ' to ',
          locale: {
            applyLabel: 'Submit',
            cancelLabel: 'Clear',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
          }
        };
        $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
        $('#reportrange').daterangepicker(optionSet1, cb);
        $('#reportrange').on('show.daterangepicker', function() {
          console.log("show event fired");
        });
        $('#reportrange').on('hide.daterangepicker', function() {
          console.log("hide event fired");
        });
        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
          console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
        });
        $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
          console.log("cancel event fired");
        });
        $('#options1').click(function() {
          $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
        });
        $('#options2').click(function() {
          $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
        });
        $('#destroy').click(function() {
          $('#reportrange').data('daterangepicker').remove();
        });
      });
    </script>
    <!-- /bootstrap-daterangepicker -->

	
	
  </body>
</html>