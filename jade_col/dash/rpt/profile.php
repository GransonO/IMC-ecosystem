<?php
//include the init file for all functions
include '../../query/php_files/core/init.php';

$Available_Points = '000';
$Company_name = '';
$Redeemptions_performed = 0;
$Redeemed_points = 0;
$TheID = '';


$the_id = $_GET['mode'];
$the_na = $_GET['name'];

//Verifys user logged in 
$USER_ID = $_SESSION['ID'];
	if($USER_ID === null){
		//Show login error
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

$members_total = "SELECT COUNT(`member_id`) AS member_count FROM `member_transactions` WHERE `member_id`='$the_id'";
$count_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_total));
$member_count = $count_query['member_count'];

//TOTAL
$members_totalX = "SELECT COUNT(`member_id`) AS member_count FROM `member_transactions`";
$count_queryX =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_totalX));
$member_countX = $count_queryX['member_count'];   
	
$members_active = "SELECT `redemptions_count` AS member_count FROM `member_loyalty_funds` WHERE `member_id`='$the_id'";
$active_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_active));
$active_members = $active_query['member_count'];

$members_dormant = "SELECT `max_trans_date` AS member_count FROM `member_loyalty_funds` WHERE `member_id`='$the_id'";
$dormant_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_dormant));
$dormant_members = $dormant_query['member_count'];

$expire = "SELECT `expired_loyalty` AS member_count FROM `member_loyalty_funds` WHERE `member_id`='$the_id'";
$expire_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$expire));
$expire_points = $expire_query['member_count'];

$members_amount = "SELECT SUM(`transaction_amount`) AS amount FROM `member_transactions` WHERE `member_id`='$the_id' ";
$amount_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_amount));
$purchase_amount = $amount_query['amount'];

//TOTAL
$members_amountX = "SELECT SUM(`transaction_amount`) AS amount FROM `member_transactions` WHERE `transaction_date` ";
$amount_queryX =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_amountX));
$purchase_amountX = $amount_queryX['amount'];

$members_avail_loyalty = "SELECT SUM(`total_points_available`) AS amount FROM `member_loyalty_funds` WHERE `member_id`='$the_id'";
$avail_loyalty_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_avail_loyalty));
$avail_loyalty_amount = number_format($avail_loyalty_query['amount']);

$total_avail_loyalty = "SELECT SUM(`total_points_available`) AS amount FROM `member_loyalty_funds`";
$total_loyalty_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$total_avail_loyalty));
$total_loyalty_amount = number_format($total_loyalty_query['amount']);

$members_loyalty = "SELECT SUM(`loyalty_earned`) AS amount FROM `member_transactions` WHERE `member_id`='$the_id'";
$loyalty_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_loyalty));
$loyalty_amount = $loyalty_query['amount'];

//TOTAL 
$members_loyaltyX = "SELECT SUM(`loyalty_earned`) AS amount FROM `member_transactions` WHERE `transaction_date`";
$loyalty_queryX =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_loyaltyX));
$loyalty_amountX = $loyalty_queryX['amount'];

//Gets the date time of redemption
date_default_timezone_set('Africa/Nairobi');
$current_date = date('d/m/Y');

//Queries customers total purchase
$purchase_query = "SELECT SUM(`transaction_amount`) AS total_purchase FROM member_transactions WHERE `member_id`='$the_id'";
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


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Interpel CFS | </title>

    <!-- Bootstrap -->
    <link href="../../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- NProgress -->
    <link href="../../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="../../../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- jVectorMap -->
    <link href="css/maps/jquery-jvectormap-2.0.3.css" rel="stylesheet"/>
	<!-- bootstrap-daterangepicker -->
    <link href="../../../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    
    <!-- Custom Theme Style -->
    <link href="../../query/dist/css/custom.css" rel="stylesheet">

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="#" class="site_title"><i class="fa fa-pagelines"></i> <span>Interpel CFS</span></a>
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

				  <li><a href="../"><i class="fa fa-home"></i>Home</a></li>
				  <li><a><i class="fa fa-group"></i> Users <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="../add_user.php">Invite new user</a></li>
                      <li><a href="../stats_use.php">View Users Stats</a></li>
                      <li><a href="../reg/">Register Customers</a></li>
                      <li><a href="../stats_guests.php">View Customer</a></li>
                    </ul> 
                  </li>   				  
				  <li><a href="../red_io.php"><i class="fa fa-trophy"></i> Customer Redemption</a>
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
				  
				  <li><a href="../arc/"><i class="fa fa-archive"></i> Archived Files </a>
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
				  <li><a href="../reg/"><i class="fa fa-group"></i> Register Member</a>
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
				  
				  <li><a href="../arc/"><i class="fa fa-archive"></i> Archived Files </a></li>
				  
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

case 'ACCOUNTING';
echo '
				  <li><a href="../stats_guests.php"><i class="fa fa-eye"></i> Users </a>
                  </li>	
				  
				  <li><a href="../reg/"><i class="fa fa-group"></i> Register Guest </a>
                  </li>
				  
				  <li><a href="../red_io.php"><i class="fa fa-trophy"></i> Reward Guest </a>
                  </li>					  

				  <li><a href="../arc/"><i class="fa fa-archive"></i> Archived Files </a></li>
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

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="row">
              <div class="col-md-12 col-xs-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Indulgence Marketing | Reports <small>Generated Loyalty Program Reports</small></h2>
                   <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                           <div class="col-xs-12 invoice-header">
                          <h1>
                              <img src="../../images/interpel.png"  height="100" width="200"> <?php echo $the_na;?> Program Report.
                              <small class="pull-right">Date: <?php echo $current_date;?></small>
                          </h1>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- info row -->
                      <div class="row invoice-info col-xs-12 col-md-12 col-sm-12">
                        <div class="col-sm-4 invoice-col">
                        <address>
							<strong>INTERPEL INVESTMENTS LTD.</strong>
							  <br>P.O.Box: 86823 80100 Kipevu, KENYA,
							  <br>Mombasa, Kenya.
							  <br>Mobile: +254-0727998811
							  <br>Mobile: +254-0738866747
							  <br>Tel: +254-0202583997/8/9
							  <br>Email: marketing@interpel.co.ke
						   </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                          <strong>Members Details:</strong>
                          <address>
                          <br><b>Total Transactions:</b>  <?php echo $member_count;?>. 
                          <br><b>Total Redemptions:</b>  <?php echo $active_members;?>.
                          <br><b>Members Current Points:</b>  <?php echo $avail_loyalty_amount;?>.
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
						<b>Report ID: Ind-<?php echo date('His'); ?>-<?php echo date('dm'); ?>-<?php echo date('Y'); ?></b>
                        <br>
                          <br>
                          <b>Total Purchase:</b>  Ksh  <?php echo number_format($purchase_amount);?>
                          <br>
                          <b>Total Loyalty:</b>  <?php echo number_format($loyalty_amount);?> Points
                          <br>
                          <b>Expired Loyalty:</b>  <?php echo number_format($expire_points);?> Points
                          <br><b>Last Trans-Date:</b>  <?php echo $dormant_members;?>.
                          </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                        <p class="lead"><?php echo $the_na.' Details';?></p>
						
												
				<div class="x_content">
                    <div class="col-xs-12 bg-white progress_summary">
						<h2>Comparison Graphs of Individual Member to Total Performance(*0.01)</h2>
                      <div class="row  col-xs-4 col-md-4 col-sm-4"  style="background:#d8fae1;">
                        <div class="progress_title">
                          <span class="left">Purchase Comparison</span>
                          <div class="clearfix"></div>
                        </div>

                        <div class="col-xs-8">
                          <div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php
							$pur = ($purchase_amount/$purchase_amountX)*10000;
							$format_pur = number_format($pur);
							echo $format_pur;
							?>"></div>
                          </div>
                        </div>
                        <div class="col-xs-2 more_info">
                          <span><?php 
						  $pur = ($purchase_amount/$purchase_amountX)*10000;
							$format_pur = number_format($pur);
							echo $format_pur;
						  ?>%(*0.01)</span>
                        </div>
                      </div>
					  
                      <div class="row   col-xs-4 col-md-4 col-sm-4" style="background:#ECFDF0;">
                        <div class="progress_title">
                          <span class="left">Loyalty Points</span>
                          <div class="clearfix"></div>
                        </div>

                        <div class="col-xs-8">
                          <div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php
							$loy = ($loyalty_amount/$loyalty_amountX)*10000;
							$format_loy = number_format($loy);
							echo $format_loy;
							?>"></div>
                          </div>
                        </div>
                        <div class="col-xs-2 more_info">
						 <span><?php 
						 $loy = ($loyalty_amount/$loyalty_amountX)*10000;
							$format_loy = number_format($loy);
							echo $format_loy;
						 ?>%(*0.01)</span>
                        </div>
                      </div>
					  
                      <div class="row   col-xs-4 col-md-4 col-sm-4">
                        <div class="progress_title">
                          <span class="left">Transactions Count</span>
                          <div class="clearfix"></div>
                        </div>

                       <div class="col-xs-8">
                          <div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php
							$count = ($member_count/$member_countX)*10000;
							$format_count = number_format($count);
							echo $format_count;
							?>"></div>
                          </div>
                        </div>
                        <div class="col-xs-2 more_info">
                          <span><?php $count = ($member_count/$member_countX)*10000;
							$format_count = number_format($count);
							echo $format_count;
							?>%(*0.01)</span>
                        </div>
                      </div>

                    </div>
                  </div>
					
                      </div>
                      <!-- /.row -->			  
					  
                      <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-xs-6">
                          <img src="../../images/logo.png" alt="indulgencemarketing">   
                          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            A report on the general loyalty program in summary </p>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                          <p class="lead">General Program Data as of <?php echo $current_date;?></p>
                          <div class="table-responsive">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <th style="width:50%">Total Earned Points:</th>
                                  <td><?php echo $loyalty;?></td>
                                </tr>
                                <tr>
                                  <th>Total redeemed:</th>
                                  <td><?php echo $redeem;?></td>
                                </tr>
                                <tr>
                                  <th>Available Points</th>
                                  <td><?php echo $total_loyalty_amount;?></td>
                                </tr>
                                <tr>
                                  <th>Expired Points:</th>
                                  <td><?php echo $expired;?></td>
                                </tr> 
								<tr>
                                  <th>Requested By :</th>
                                  <td><?php echo $username;?></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <!-- /.col -->
						
                      </div>
                      <!-- /.row -->

                      <!-- this row will not appear when printing -->
                      <div class="row no-print">
                        <div class="col-xs-12">
                          <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                        </div>
                      </div>
                    </section>
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
	<!-- morris.js -->
    <script src="../../../vendors/raphael/raphael.min.js"></script>
    <script src="../../../vendors/morris.js/morris.min.js"></script>
    <!-- gauge.js -->
    <script src="../../../vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../../../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    
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