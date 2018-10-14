<?php
//include the init file for all functions
include '../../query/php_files/core/init.php';

	//Verifys user logged in 
	$USER_ID = $_SESSION['ID'];
		if($USER_ID === null){
			//Show loggin error
	header('Location: ../../ini/login_error.html');
		
		}else{
			//do nothing
		}
//Get User Details
$User_Data = "SELECT * FROM privileged_users WHERE `password` = '$USER_ID'"; 
$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
$username = $U_query['username'];
$status = $U_query['redeemer'];
$profile = $U_query['profile'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Private Collection | </title>

    <!-- Bootstrap -->
    <link href="../../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../../../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../../../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../../query/dist/css/custom.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="../" class="site_title"><i class="fa fa-pagelines"></i> <span>Private Collection</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <img src="../../images/user.png" alt="..." class="img-circle profile_img">
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

				  <li><a><i class="fa fa-institution"></i> Property <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
					  <li><a href="../">Naivasha Kongoni</a></li>
                      <li><a href="#">Wileli House</a></li>
                      <li><a href="#">Swahili House</a></li>
                      <li><a href="#">Poa Place Resort</a></li>
                    </ul>
                  </li>
				  <li><a><i class="fa fa-group"></i> Users <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="../add_user.php">Invite new user</a></li>
                      <li><a href="../stats_use.php">View Users Stats</a></li>
                      <li><a href="../reg/">Register Guest</a></li>
                      <li><a href="../stats_guests.php">View Guests</a></li>
                    </ul> 
                  </li>   				  
				  <li><a href="../guest_query.php"><i class="fa fa-trophy"></i> Reward Guest </a>
                  </li>	
				  <li><a><i class="fa fa-edit"></i> Receipts <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="../tbs/receipts.php"> Program Receipts</a></li>
                    </ul> 
                  </li>
				  
				  
			  <li><a><i class="fa fa-bell-o"></i>Engagement Platforms <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="../eng/mailp.php">Email Platform</a></li>
                    </ul> 
                  </li>
				  
				  <li><a> <i class="fa fa-table"></i>Members Tables<span class="fa fa-chevron-down"></span></a>
				   <ul class="nav child_menu">
                      <li><a href="../tbs/contacts.php?mode=contacts">Members Contacts</a></li>
                      <li><a href="../tbs/loyalty.php?mode=loyalty">Loyalty Funds</a></li>
                      <li><a href="../tbs/redemptions.php?mode=request">Redemption</a></li>
                      <li><a href="../tbs/transactions.php?mode=transactions">Transaction</a></li>
                    </ul> 
				  </li>	
				  
				  <li><a><i class="fa fa-lightbulb-o"></i> Program Reports <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="../rpt/individual_property.php"> Individual Member Report </a></li>
                      <li><a href="../rpt/general_report.php"> General Program Report </a></li>
                    </ul> 
                  </li>
				  
				  <li><a href="../itspt/"><i class="fa fa-desktop"></i> IT & Support </a>
                  </li>	
				  <li><a  href="../itspt/support.php"><i class="fa fa-gear"></i> SYSTEMS SUPPORT </a>
                  </li>	
				  <li><a href="http://178.62.100.247/interpel/interpelrewards/production/T&C/INTERPEL%20REWARDS%20PROGRAM%20TERMS%20AND%20CONDITIONS.pdf"><i class="fa fa-file-pdf-o"></i> Terms & Conditions</a>
                  </li> 
				  

';
break;

case 'MARKETING';
echo '
				  <li><a href="../reg/"><i class="fa fa-group"></i> Register Guests </a>
                  </li>   
				  <li><a href="../tbs/receipts.php"><i class="fa fa-edit"></i> Reward Receipts </a>
                  </li>
				  <li><a> <i class="fa fa-table"></i>Members Tables<span class="fa fa-chevron-down"></span></a>
				   <ul class="nav child_menu">
                      <li><a href="../tbs/contacts.php?mode=contacts">Members Contacts</a></li>
                      <li><a href="../tbs/loyalty.php?mode=loyalty">Loyalty Funds</a></li>
                      <li><a href="../tbs/redemptions.php?mode=request">Redemption</a></li>
                      <li><a href="../tbs/transactions.php?mode=transactions">Transaction</a></li>
                    </ul> 
				  </li>	 
				  <li><a href="../stats_guests.php"><i class="fa fa-eye"></i> View Guests </a>
                  </li>
				  
			  <li><a><i class="fa fa-bell-o"></i>Engagement Platforms <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="../eng/mailp.php">Email Platform</a></li>
                    </ul> 
                  </li>
				  
				  <li><a><i class="fa fa-lightbulb-o"></i> Program Reports <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="../rpt/individual_property.php"> Individual Member Report </a></li>
                      <li><a href="../rpt/general_report.php"> General Program Report </a></li>
                    </ul> 
                  </li>
				  	
                  </li>	
				  <li><a href="http://178.62.100.247/interpel/interpelrewards/production/T&C/INTERPEL%20REWARDS%20PROGRAM%20TERMS%20AND%20CONDITIONS.pdf"><i class="fa fa-file-pdf-o"></i> Terms & Conditions</a>
                  </li> 
				  

';
break;

case 'FRONTDESK';
echo '
				  <li><a href="../stats_guests.php"><i class="fa fa-eye"></i> Users </a>
                  </li>	
				  
				  <li><a href="../reg/"><i class="fa fa-group"></i> Register Guest </a>
                  </li>
				  
				  <li><a href="../guest_query.php"><i class="fa fa-trophy"></i> Reward Guest </a>
                  </li>					  

';
break;
case 'IT';
echo '
				  <li><a href="../itspt/index.php"><i class="fa fa-cloud-upload"></i> FILES UPLOAD </a>                    
                  </li>	
				  <li><a  href="../itspt/support.php"><i class="fa fa-gear"></i> SYSTEMS SUPPORT </a>
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
              <a href="../../query/php_files/core/logout.php" data-toggle="tooltip" data-placement="top" title="Logout">
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

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="logout.php">Log Out  .<span><i class="glyphicon glyphicon-off"></i></span>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
		  	<div class="row">
			
			<!-- First Table -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo 'Member Summary Report'; ?></h2>
                    <div class="clearfix"></div>
                  </div>
				  
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                     Program Report
					 </p>
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Member ID</th>
                          <th>Company Name</th>
                          <th>Last Transaction Date</th>
                          <th>Total Purchase</th>
                          <th>Total Points Earned</th>
                          <th>Transaction Count</th>
                          <th>Points Redeemed</th>
                          <th>Redemptions Count</th>
                          <th>Expired Points</th>
                        </tr>
                      </thead>

	<tbody>
		<?php
			if(empty($_POST['from_date'])===false){
								
				$from_date = $_POST['from_date'];
				$to_date = $_POST['to_date'];
				
				//DELETE TABLE CONTENTS;
				$CLEAR_TABLE = "TRUNCATE TABLE `custom_sales_reports_table` ";
				$CLEAR_TABLE_DONE = mysqli_query(Connect_Database(), $CLEAR_TABLE);
				
				//echo '[{custom_sales_reports_table}] ->Target table TRUNCATED </br>';
				
				//1. GET TOTAL MEMBERS COUNT
				$query_count = "SELECT COUNT(`member_id`) AS ID FROM `member_loyalty_funds` where `max_trans_date`  >= '$from_date' AND `max_trans_date` <= '$to_date'";
				$result_count = mysqli_fetch_assoc(mysqli_query(Connect_Database(),$query_count));
				$TOTAL_COUNT = $result_count['ID'];

				echo ' Total member count :'.$TOTAL_COUNT;
				
				//2. GET MEMBERS ID AND NAMES FROM LOYALTY FUNDS TABLE.
				$query = "SELECT * FROM `member_loyalty_funds` where `max_trans_date`  >= '$from_date' AND `max_trans_date` <= '$to_date'";	
				$result = mysqli_query(Connect_Database(),$query);
				
				if($result){
				$arrayofrows = array();
				while($row = mysqli_fetch_all($result)){
					for ($x = 0; $x <= $TOTAL_COUNT - 1; $x++){ 
				   $arrayofrows = $row;
					//Change this from [5] to [6] on uploading
					//GETS GENERAL DATA FROM THE LOYALTY TABLE
				   $member_id = $arrayofrows[$x][5];
				   $member_name = $arrayofrows[$x][6];
				   $last_purchase_date = $arrayofrows[$x][2];
				   
				//	echo 'Member ID: '.$member_id.'</br>';
				//	echo 'Member Name: '.$member_name.'</br>';
				//	echo 'Last purchase date: '.$last_purchase_date.'</br>';
					
						
					//GETS SPECIFIC DATA FROM THE TRANSACTIONS TABLE 
					//Queries latest transaction date
					$PHASE_QUERY  = "SELECT COUNT(`member_id`) AS ID,SUM(`transaction_amount`) AS TRANSACTION,SUM(`loyalty_earned`) AS LOYALTY FROM `member_transactions` WHERE `transaction_date`  >= '$from_date' AND `transaction_date` <= '$to_date' AND `member_id` = '$member_id' ";
					$PHASE_QUERY_RESULT =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$PHASE_QUERY));
					
					$PHASE_PURCHASE = $PHASE_QUERY_RESULT['TRANSACTION'];
					$PHASE_LOYALTY = $PHASE_QUERY_RESULT['LOYALTY'];
					$PHASE_COUNT = $PHASE_QUERY_RESULT['ID'];

				//	echo '</br>PHASE_QUERY :'.$PHASE_QUERY;
				//	echo '</br>PHASE_PURCHASE :'.$PHASE_PURCHASE;
				//	echo '</br>PHASE_LOYALTY :'.$PHASE_LOYALTY;
				//	echo '</br>PHASE_COUNT :'.$PHASE_COUNT ;
					
					//---Queries latest transaction date
					$PHASE_REDEMPTION  = "SELECT COUNT(`member_id`) AS ID,SUM(`redeemed_amount`) AS AMT FROM `member_redemption_transactions` WHERE `created_at`  >= '$from_date' AND `created_at` <= '$to_date' AND `member_id` = '$member_id' ";
					$PHASE_REDEMPTION_RESULT =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$PHASE_REDEMPTION));
					
					$PHASE_REDEMPTION_AMT = $PHASE_REDEMPTION_RESULT['AMT'];
					if($PHASE_REDEMPTION_AMT === null){
						$PHASE_REDEMPTION_AMT = 0;
					};
					$PHASE_REDEMPTION_COUNT = $PHASE_REDEMPTION_RESULT['ID'];
					//$PHASE_REDEMPTION_DATE = $PHASE_REDEMPTION_RESULT['MAX_DATE'];
					
					
				//	echo '</br>PHASE_REDEMPTION_QUERY :'.$PHASE_REDEMPTION;
					
				//	echo '</br>PHASE_REDEMPTION_AMT :'.$PHASE_REDEMPTION_AMT;
				//	echo '</br>PHASE_REDEMPTION_COUNT :'.$PHASE_REDEMPTION_COUNT;
				//	echo '</br>PHASE_REDEMPTION_DATE :'.$PHASE_REDEMPTION_DATE;
					//------------------------------------------------
					//---Queries expired points
					$PHASE_EXPIRY  = "SELECT SUM(`expired_points`) AS POINTS FROM `points_expiry` WHERE `expiry_date`  >= '$from_date' AND `expiry_date` <= '$to_date' AND `member_id` = '$member_id' ";
					$PHASE_EXPIRY_RESULT =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$PHASE_EXPIRY));
					
					$PHASE_EXPIRY_AMT = $PHASE_EXPIRY_RESULT['POINTS'];	
					if($PHASE_EXPIRY_AMT === null){
						$PHASE_EXPIRY_AMT = 0;
					}
					
					
				//	echo '</br>PHASE_EXPIRY_AMT :'.$PHASE_EXPIRY_AMT;
				//	echo '</br>PHASE_REDEMPTION_COUNT :'.$PHASE_REDEMPTION_COUNT;
				//	echo '</br>PHASE_REDEMPTION_AMT :'.$PHASE_REDEMPTION_AMT;
					
					
				   //INSERT THE ACQUIRED DATA INTO THE TABLE
					$INSERT_STATEMENT = "INSERT INTO `custom_sales_reports_table` (
					`member_id`, `member_name`, `last_trans_date`, `total_purchase`, `total_points`, `transactions_count`, `total_redemptions`,`redemptions_count`,`expired_points`) VALUES (
					'$member_id', '$member_name', '$last_purchase_date', '$PHASE_PURCHASE', '$PHASE_LOYALTY', '$PHASE_COUNT', '$PHASE_REDEMPTION_AMT','$PHASE_REDEMPTION_COUNT','$PHASE_EXPIRY_AMT');";
					
				//	echo $INSERT_STATEMENT;
					$INSERT_STATEMENT_DONE = mysqli_query(Connect_Database(), $INSERT_STATEMENT);					
						$x = $x + 1;
						
					}
				}
						
						//Queries customer specific transactions
						$Table_query  = "SELECT * FROM `custom_sales_reports_table`";
						$table_query = mysqli_query(Connect_Database(),$Table_query);
						
						while($table_data = mysqli_fetch_assoc($table_query)){
								echo "<tr>";
								echo "<td>" .$table_data['member_id'] . "</td>" ; 
								echo "<td>" .$table_data['member_name'] . "</td>" ; 		
								echo "<td>" .$table_data['last_trans_date'] . "</td>" ; 		
								echo "<td>" .$table_data['total_purchase'] . "</td>" ; 
								echo "<td>" .$table_data['total_points'] . "</td>" ; 
								echo "<td>" .$table_data['transactions_count'] . "</td>" ; 
								echo "<td>" .$table_data['total_redemptions'] . "</td>" ; 
								echo "<td>" .$table_data['redemptions_count'] . "</td>" ; 
								echo "<td>" .$table_data['expired_points'] . "</td>" ; 
								echo "</tr>"; 
					}
				}
				
			}
			else if(empty($_POST['member_id'])=== false){
				
				$from_date = $_POST['sp_from_date'];
				$to_date = $_POST['sp_to_date'];
				$member_id = $_POST['member_id'];
				
				//DELETE TABLE CONTENTS;
				$CLEAR_TABLE = "TRUNCATE TABLE `custom_sales_reports_table` ";
				$CLEAR_TABLE_DONE = mysqli_query(Connect_Database(), $CLEAR_TABLE);
				
				//echo '[{custom_sales_reports_table}] ->Target table TRUNCATED </br>';
				
				//1. GET TOTAL MEMBERS COUNT
				$query_count = "SELECT COUNT(`member_id`) AS ID FROM `member_loyalty_funds` where `max_trans_date`  >= '$from_date' AND `max_trans_date` <= '$to_date' AND `member_id`='$member_id'";
				$result_count = mysqli_fetch_assoc(mysqli_query(Connect_Database(),$query_count));
				$TOTAL_COUNT = $result_count['ID'];

				echo ' Total member count :'.$TOTAL_COUNT;
				
				//2. GET MEMBERS ID AND NAMES FROM LOYALTY FUNDS TABLE.
				$query = "SELECT * FROM `member_loyalty_funds` where `max_trans_date`  >= '$from_date' AND `max_trans_date` <= '$to_date' AND `member_id`='$member_id'";	
				$result = mysqli_query(Connect_Database(),$query);
				
				if($result){
				$arrayofrows = array();
				while($row = mysqli_fetch_all($result)){
					for ($x = 0; $x <= $TOTAL_COUNT - 1; $x++){ 
				   $arrayofrows = $row;
					//Change this from [5] to [6] on uploading
					//GETS GENERAL DATA FROM THE LOYALTY TABLE
				   $member_id = $arrayofrows[$x][5];
				   $member_name = $arrayofrows[$x][6];
				   $last_purchase_date = $arrayofrows[$x][2];
				   
				//	echo 'Member ID: '.$member_id.'</br>';
				//	echo 'Member Name: '.$member_name.'</br>';
				//	echo 'Last purchase date: '.$last_purchase_date.'</br>';
					
						
					//GETS SPECIFIC DATA FROM THE TRANSACTIONS TABLE 
					//Queries latest transaction date
					$PHASE_QUERY  = "SELECT COUNT(`member_id`) AS ID,SUM(`transaction_amount`) AS TRANSACTION,SUM(`loyalty_earned`) AS LOYALTY FROM `member_transactions` WHERE `transaction_date`  >= '$from_date' AND `transaction_date` <= '$to_date' AND `member_id` = '$member_id' ";
					$PHASE_QUERY_RESULT =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$PHASE_QUERY));
					
					$PHASE_PURCHASE = $PHASE_QUERY_RESULT['TRANSACTION'];
					$PHASE_LOYALTY = $PHASE_QUERY_RESULT['LOYALTY'];
					$PHASE_COUNT = $PHASE_QUERY_RESULT['ID'];

				//	echo '</br>PHASE_QUERY :'.$PHASE_QUERY;
				//	echo '</br>PHASE_PURCHASE :'.$PHASE_PURCHASE;
				//	echo '</br>PHASE_LOYALTY :'.$PHASE_LOYALTY;
				//	echo '</br>PHASE_COUNT :'.$PHASE_COUNT ;
					
					//---Queries latest transaction date
					$PHASE_REDEMPTION  = "SELECT COUNT(`member_id`) AS ID,SUM(`redeemed_amount`) AS AMT,MAX(`created_at`) AS MAX_DATE FROM `member_redemption_transactions` WHERE `created_at`  >= '$from_date' AND `created_at` <= '$to_date' AND `member_id` = '$member_id' ";
					$PHASE_REDEMPTION_RESULT =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$PHASE_REDEMPTION));
					
					$PHASE_REDEMPTION_AMT = $PHASE_REDEMPTION_RESULT['AMT'];
					if($PHASE_REDEMPTION_AMT === null){
						$PHASE_REDEMPTION_AMT = 0;
					};
					$PHASE_REDEMPTION_COUNT = $PHASE_REDEMPTION_RESULT['ID'];
					$PHASE_REDEMPTION_DATE = $PHASE_REDEMPTION_RESULT['MAX_DATE'];
					
					
				//	echo '</br>PHASE_REDEMPTION_QUERY :'.$PHASE_REDEMPTION;
					
				//	echo '</br>PHASE_REDEMPTION_AMT :'.$PHASE_REDEMPTION_AMT;
				//	echo '</br>PHASE_REDEMPTION_COUNT :'.$PHASE_REDEMPTION_COUNT;
				//	echo '</br>PHASE_REDEMPTION_DATE :'.$PHASE_REDEMPTION_DATE;
					//------------------------------------------------
					
					//---Queries expired points
					$PHASE_EXPIRY  = "SELECT SUM(`expired_points`) AS POINTS FROM `points_expiry` WHERE `expiry_date`  >= '$from_date' AND `expiry_date` <= '$to_date' AND `member_id` = '$member_id' ";
					$PHASE_EXPIRY_RESULT =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$PHASE_EXPIRY));					
					$PHASE_EXPIRY_AMT = $PHASE_EXPIRY_RESULT['POINTS'];
					if($PHASE_EXPIRY_AMT === null){
						$PHASE_EXPIRY_AMT = 0;
					}
					
					
				//	echo '</br>PHASE_EXPIRY_AMT :'.$PHASE_EXPIRY_AMT;
				//	echo '</br>PHASE_REDEMPTION_COUNT :'.$PHASE_REDEMPTION_COUNT;
				//	echo '</br>PHASE_REDEMPTION_AMT :'.$PHASE_REDEMPTION_AMT;
					
				   //INSERT THE ACQUIRED DATA INTO THE TABLE
					$INSERT_STATEMENT = "INSERT INTO `custom_sales_reports_table` (
					`member_id`, `member_name`, `last_trans_date`, `total_purchase`, `total_points`, `transactions_count`, `total_redemptions`,`redemptions_count`,`expired_points`) VALUES (
					'$member_id', '$member_name', '$last_purchase_date', '$PHASE_PURCHASE', '$PHASE_LOYALTY', '$PHASE_COUNT', '$PHASE_REDEMPTION_AMT','$PHASE_REDEMPTION_COUNT','$PHASE_EXPIRY_AMT');";
					
				//	echo $INSERT_STATEMENT;
					$INSERT_STATEMENT_DONE = mysqli_query(Connect_Database(), $INSERT_STATEMENT);					
						$x = $x + 1;
						
					}
				}
						//Queries customer specific transactions
						$Table_query  = "SELECT * FROM `custom_sales_reports_table`";
						$table_query = mysqli_query(Connect_Database(),$Table_query);
						
						while($table_data = mysqli_fetch_assoc($table_query)){
								echo "<tr>";
								echo "<td>" .$table_data['member_id'] . "</td>" ; 
								echo "<td>" .$table_data['member_name'] . "</td>" ; 		
								echo "<td>" .$table_data['last_trans_date'] . "</td>" ; 		
								echo "<td>" .$table_data['total_purchase'] . "</td>" ; 
								echo "<td>" .$table_data['total_points'] . "</td>" ; 
								echo "<td>" .$table_data['transactions_count'] . "</td>" ; 
								echo "<td>" .$table_data['total_redemptions'] . "</td>" ; 
								echo "<td>" .$table_data['redemptions_count'] . "</td>" ; 
								echo "<td>" .$table_data['expired_points'] . "</td>" ; 
								echo "</tr>"; 
					}
				}
				
			}
?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="col-md-6 col-sm-6 col-xs-12"   style="text-align: center;">
			 	<div class="x_panel">
                    <h2>Filter data according to transaction date</h2>
                    <div class="clearfix"></div>
                  </div>
					
					<div class="x_panel">
					<form action="" method="post" style="text-align: center;>
					<div class="form-group"  style="text-align: center;">
					<label class="control-label col-md-6 col-sm-6 col-xs-12" for="from-date">Date from: <span class="required">*</span>
                        </label>
                        <div class="input-group col-md-6 col-sm-6 col-xs-12  top_search" >
						<input type="date" class="form-control" name="from_date" required="required"/>
						<span class="input-group-btn">
						<button class="btn btn-default" > .</button>
						</span>
                      </div> 
					  <div class="form-group"  style="text-align: center;">
					<label class="control-label col-md-6 col-sm-6 col-xs-12" for="from-date">Date to: <span class="required">*</span>
                        </label>
                        <div class="input-group col-md-6 col-sm-6 col-xs-12  top_search" >
						<input type="date" class="form-control" name="to_date" required="required"/>
						<span class="input-group-btn">
						<button class="btn btn-default" > .</button>
						</span>
                      </div> 
                      </div> 
                      <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 "  style="text-align: center;">
                          <button type="submit" class="btn btn-success">Submit</button>
                          <button type="submit" class="btn btn-primary">Cancel</button>
                        </div>
                      </div>
                      </form>  
                    </div>
                    </div>

			<div class="col-md-6 col-sm-6 col-xs-12"   style="text-align: center;">
			 	<div class="x_panel">
                    <h2>Filter data according to member id and transaction date</h2>
                    <div class="clearfix"></div>
                  </div>
					
					<div class="x_panel">
					<form action="" method="post" style="text-align: center;>
					<div class="form-group"  style="text-align: center;">
					<label class="control-label col-md-6 col-sm-6 col-xs-12" for="from-date">Date from: <span class="required">*</span>
                        </label>
                        <div class="input-group col-md-6 col-sm-6 col-xs-12  top_search" >
						<input type="date" class="form-control" name="sp_from_date" required="required"/>
						<span class="input-group-btn">
						<button class="btn btn-default" > .</button>
						</span>
                      </div> 
					  <div class="form-group"  style="text-align: center;">
					<label class="control-label col-md-6 col-sm-6 col-xs-12" for="from-date">Date to: <span class="required">*</span>
                        </label>
                        <div class="input-group col-md-6 col-sm-6 col-xs-12  top_search" >
						<input type="date" class="form-control" name="sp_to_date" required="required"/>
						<span class="input-group-btn">
						<button class="btn btn-default" > .</button>
						</span>
                      </div> 
                      </div> 

					  
					<div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 "  style="text-align: left;">
                        <div class="input-group col-md-12 col-sm-12 col-xs-12  top_search" >
						<input type="name" class="form-control" placeholder = "Member ID*" name="member_id" required="required"/>
						<span class="input-group-btn">
						<button class="btn btn-default" > .</button>
						</span>
                      </div> 
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 "  style="text-align: right;">
                          <button type="submit" class="btn btn-success">Submit</button>
                          <button type="submit" class="btn btn-primary">Cancel</button>
                        </div>
                      </div>
                      </form>  
                    </div>
                    </div>
                 <div class="clearfix"></div>
				</div>
			</div>
			
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            <a href="https://www.indulgencemarketing.co.ke">Indulgence Marketing 2016 </a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../../../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../../../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../../../vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="../../../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- jQuery Sparklines -->
    <script src="../../../vendors/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <!-- Flot -->
    <script src="../../../vendors/Flot/jquery.flot.js"></script>
    <script src="../../../vendors/Flot/jquery.flot.pie.js"></script>
    <script src="../../../vendors/Flot/jquery.flot.categories.js"></script>
    <script src="../../../vendors/Flot/jquery.flot.stack.js"></script>
    <script src="../../../vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="js/flot/jquery.flot.orderBars.js"></script>
    <script src="js/flot/date.js"></script>
    <script src="js/flot/jquery.flot.spline.js"></script>
    <script src="js/flot/curvedLines.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>
    
    <!-- Custom Theme Scripts -->
    <script src="../../query/dist/js/custom.min.js"></script>

    <!-- Datatables -->
    <script>	
      $(document).ready(function() {
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            $("#datatable-buttons").DataTable({
              dom: "Bfrtip",
              buttons: [
                {
                  extend: "copy",
                  className: "btn-sm"
                },
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdfHtml5",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              responsive: true
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();

        $('#datatable').dataTable();
		
		$('#datatable').dataTable( {
		       "pageLength": 50
		    } );
		 
        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        var table = $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        TableManageButtons.init();
      });
    </script>
    <!-- /Datatables -->

  </body>
</html>