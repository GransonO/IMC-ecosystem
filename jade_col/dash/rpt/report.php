<?php
//include the init file for all functions
include '../../query/php_files/core/init.php';

$Available_Points = '000';
$Company_name = '';
$Redeemptions_performed = 0;
$Redeemed_points = 0;
$TheID = '';

$the_mode = $_GET['mode'];

$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];

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

$members_total = "SELECT COUNT(`member_id`) AS member_count FROM `member_loyalty_funds`";
$count_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_total));
$member_count = $count_query['member_count'];

$members_active = "SELECT COUNT(`member_id`) AS member_count FROM `member_loyalty_funds` WHERE `loyalty_value_earned` > 0";
$active_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_active));
$active_members = $active_query['member_count'];

$members_dormant = "SELECT COUNT(`member_id`) AS member_count FROM `member_loyalty_funds` WHERE `loyalty_value_earned` = 0";
$dormant_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_dormant));
$dormant_members = $dormant_query['member_count'];

$members_amount = "SELECT SUM(`transaction_amount`) AS amount FROM `member_transactions` WHERE `transaction_date` BETWEEN '$from_date' AND '$to_date' ";
$amount_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_amount));
$purchase_amount = number_format($amount_query['amount']);

$members_avail_loyalty = "SELECT SUM(`total_points_available`) AS amount FROM `member_loyalty_funds`";
$avail_loyalty_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_avail_loyalty));
$avail_loyalty_amount = number_format($avail_loyalty_query['amount']);

$members_loyalty = "SELECT SUM(`loyalty_value_earned`) AS amount FROM `member_loyalty_funds`";
$loyalty_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_loyalty));
$loyalty_amount = number_format($loyalty_query['amount']);

//Gets the date time of redemption
date_default_timezone_set('Africa/Nairobi');
$current_date = date('d/m/Y');

//Queries customers total purchase
$purchase_query = "SELECT SUM(`transaction_amount`) AS total_purchase FROM member_transactions  WHERE `transaction_date` BETWEEN '$from_date' AND '$to_date' ";
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

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Jade Collections | </title>

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
              <a href="#" class="site_title"><i class="fa fa-pagelines"></i> <span>Jade Collections</span></a>
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
				  <!--li><a href="http://178.62.100.247/interpel/interpelrewards/production/T&C/INTERPEL%20REWARDS%20PROGRAM%20TERMS%20AND%20CONDITIONS.pdf"><i class="fa fa-file-pdf-o"></i> Terms & Conditions</a>
                  </li--> 
				  

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
				  <!--li><a href="http://178.62.100.247/interpel/interpelrewards/production/T&C/INTERPEL%20REWARDS%20PROGRAM%20TERMS%20AND%20CONDITIONS.pdf"><i class="fa fa-file-pdf-o"></i> Terms & Conditions</a>
                  </li--> 
				  

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
                    <h2>Indulgence Marketing | Reports <small>Generated Loyalty Program Report from : <?php echo $from_date.' to '.$to_date;?></small></h2>
                   <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                          <h1>
                              <img src="../../images/jade_logo.png"  height="100" width="200"> <?php echo $the_mode;?> Auto Program Report.
                              <small class="pull-right">Date: <?php echo $current_date;?></small>
                          </h1>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- info row -->
                      <div class="row invoice-info col-xs-12 col-md-12 col-sm-12">
                        <div class="col-sm-4 invoice-col">
                          <address>
							<strong>Jade Collections Kenya.</strong>
							  <br>P.O.Box: 12999-00400,
							  <br>Head office Block 54, Alpha Center,
							  <br>Mombasa Road, Nairobi Kenya .
							  <br>Tel: 0800 722 722
							  <!--br>Email: -- -- -- -- --> 
                            </address>
				         </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                          <strong>Members Details:</strong>
                          <address>
                          <br><b>Total Members:</b>  <?php echo $member_count;?>. 
                          <br><b>Active Members:</b>  <?php echo $active_members;?>.
                          <br><b>Dormant Members:</b>  <?php echo $dormant_members;?>.
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
						<b>Report ID: Ind-<?php echo date('His'); ?>-<?php echo date('dm')-91; ?>-2017</b>
                          <br>
                          <br>
                          <b>Total Purchase:</b>  Ksh  <?php echo $purchase_amount;?>
                          <br>
                          <b>Total Loyalty:</b>  <?php echo $loyalty_amount;?> Points
                          <br>
                          <b>Requested By :</b>  <?php echo $username;?>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                        <p class="lead">Store Details:</p>
                      <!-- Table row -->
                      <div class="row">					  
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Company Name</th>
                                <th>Member ID</th>
                                <th>Loyalty Value Earned:</th>
                                <th>Points Redeemed:</th>
                                <th>Last Redeem Date:</th>
                              </tr>
                            </thead>
                            <tbody>
							<?php 
							
					//Queries customer table values
					$Table_query  = "SELECT * FROM `member_loyalty_funds` ORDER BY `total_points_redeemed` DESC LIMIT 5;";
					$tablequery = mysqli_query(Connect_Database(),$Table_query);
					while($table_data = mysqli_fetch_assoc($tablequery)){
						echo "<tr>";
						echo "<td>" .$table_data['company_name'] . "</td>" ; 	
						echo "<td>" .$table_data['member_id'] . "</td>" ; 	
						echo "<td>" .$table_data['loyalty_value_earned'] . "</td>" ; 	
						echo "<td>" .$table_data['total_points_redeemed'] . "</td>" ; 	
						echo "<td>" .$table_data['last_redeem_date'] . "</td>" ; 	
						echo "</tr>";  
					}
						?>
                              </tbody>
                          </table>
                        </div>
                        <!-- /.col -->
						
												
			<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
					<h1>Graph on programs Progress</h1>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="demo-container" style="height:380px">
                        <div id="placeholder33x" class="demo-placeholder" style="height:370px"></div><!-- This line houses the graph-->
                      </div>
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
                          <p class="lead">Program Data as of <?php echo $current_date;?></p>
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
                                  <td><?php echo $avail_loyalty_amount;?></td>
                                </tr>
                                <tr>
                                  <th>Expired Points:</th>
                                  <td><?php echo $expired;?></td>
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
		//generate random number for charts
				if(mon == 11){
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
					if(mon == 10){
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
				}else if(mon == 9){
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
				}else if(mon == 8){
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
				}else if(mon == 7){
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
				}else if(mon == 6){
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
				}else if(mon == 5){
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
				}else if(mon == 4){
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
				}else if(mon == 3){
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
				}else if(mon == 2){
					
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
					
				}else if(mon == 1){
					
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
					
				}else if(mon == 0){
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