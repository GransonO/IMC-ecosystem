<?php
//include the init file for all functions
include '../../query/php_files/core/init.php';

$Available_Points = '000';
$Company_name = '';

$id = $_GET['id'];
//$requested = number_format($_GET['requested']);

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

$members_date = "SELECT MAX(`transaction_date`) AS amount FROM `member_transactions` where `member_id` = '$id'";
$date_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_date));
$purchase_date = $date_query['amount'];

$members_avail_loyalty = "SELECT SUM(`total_points_available`) AS amount,`company_name` AS company, `redemptions_count` AS redemptions FROM `member_loyalty_funds` where `member_id` = '$id'";
$avail_loyalty_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_avail_loyalty));
$avail_loyalty_amount = number_format($avail_loyalty_query['amount']);
$company_name = $avail_loyalty_query['company'];
$redemptions = $avail_loyalty_query['redemptions'];

$members_loyalty = "SELECT SUM(`loyalty_value_earned`) AS amount FROM `member_loyalty_funds` where `member_id` = '$id'";
$loyalty_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_loyalty));
$loyalty_amount = number_format($loyalty_query['amount']);

//Gets the date time of redemption
date_default_timezone_set('Africa/Nairobi');
$current_date = date('d/m/Y');

//Queries customers total redeemed loyalty
$redeemed_query = "SELECT SUM(`total_points_redeemed`) AS redeemed_loyalty FROM member_loyalty_funds where `member_id` = '$id'";
$rquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$redeemed_query));
$redeem_format_number = $rquery['redeemed_loyalty'];
$redeem = number_format($redeem_format_number);

//Queries customers total Expired loyalty
$expired_query = "SELECT SUM(`expired_loyalty`) AS expired_loyalty FROM member_loyalty_funds where `member_id` = '$id'";
$equery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$expired_query));
$expired_format_number = $equery['expired_loyalty'];
$expired = number_format($expired_format_number);

//Redemption ID
$redeem_query = "SELECT `redemption_id` AS redeemed_id,`created_at` as last_red_date,`redeemed_amount` as requested FROM `member_redemption_transactions` WHERE `member_id` = '$id' ORDER BY `created_at` DESC LIMIT 1;";
$rquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$redeem_query));
$redeemed_id = $rquery['redeemed_id'];
$last_red_date = $rquery['last_red_date'];
$requested = $rquery['requested'];

//Redemption ID
$poc_query = "SELECT `poc_name` AS poc_name FROM `member_contact` WHERE `member_id` = '$id';";
$pquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$poc_query));
$poc_name = $pquery['poc_name'];



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
                    <h2>Interpel CFS | Receipts <small> Redemption</small></h2>
                   <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                          <h1>
                              <img src="../../images/interpel.png"  height="100" width="200"> <?php echo $company_name;?> Redemption Receipt.
                              <small class="pull-Left">Date: <?php echo $current_date;?></small>
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
                          <br><b>Member Name:</b>  <?php echo $company_name;?>. 
                          <br><b>Members ID:</b>  <?php echo $id;?>.
                          <br><b>Person of Contact:</b>  <?php echo $poc_name;?>.
                          <br><b>Last Purchase Date:</b>  <?php echo $purchase_date;?>.
                          <br><b>Redemption Amount:</b>  <?php echo $requested;?>.
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
						<b>Redemption ID: <?php echo $redeemed_id;?></b>
                          <br>
                          <br>
                          <b>Total Points Redeemed:</b>  <?php echo $redeem;?> Points
                          <br>
                          <b>Total Points Expired:</b> <?php echo $expired;?> Points 
                          <br>
                          <b>Account Balance:</b>  <?php echo $avail_loyalty_amount;?> Points
                          <br>
                          <b>Number of redemptions made :</b>  <?php echo $redemptions;?>
                          <br>
                          <b>Last redemption date :</b>  <?php echo $last_red_date;?>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                        <p class="lead">Customers Redemption Details :</p>
                      <!-- Table row -->
                      <div class="row">					  
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Company Name</th>
                                <th>Member ID</th>
                                <th>Amount Redeemed</th>
                                <th>Redemption Id</th>
                                <th>Transacted By</th>
                                <th>Redemption Date</th>
                              </tr>
                            </thead>
                            <tbody>
							<?php 
							
					//Queries customer table values
					$Table_query  = "SELECT * FROM `member_redemption_transactions` WHERE `member_id`='$id' ORDER BY `created_at` DESC LIMIT 1;";
					$tablequery = mysqli_query(Connect_Database(),$Table_query);
					while($table_data = mysqli_fetch_assoc($tablequery)){
						echo "<tr>";
						echo "<td>" .$table_data['company_name'] . "</td>" ; 	
						echo "<td>" .$table_data['member_id'] . "</td>" ; 	
						echo "<td>" .$table_data['redeemed_amount'] . "</td>" ; 	
						echo "<td>" .$table_data['redemption_id'] . "</td>" ; 	
						echo "<td>" .$table_data['redeemed_by'] . "</td>" ; 	
						echo "<td>" .$table_data['created_at'] . "</td>" ; 	
						echo "</tr>";  
					}
						?>
                              </tbody>
                          </table>
                        </div>
                        <!-- /.col -->
					</div>
                      <!-- /.row -->			  
					  
                      <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-xs-6">
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                          <p class="lead">Transaction Date: <?php echo $last_red_date;?></p>
                          <div class="table-responsive">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <th style="width:50%">Transacted by:</th>
                                  <td><?php echo $username;?></td>
                                </tr>
                                <tr>
                                  <th>Sign:</th>
                                  <td>_ _ _ _ _ _ _ _ _ _ _ </td>
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

        <!-- footer content 
        <footer>
          <div class="pull-right">
		  <a href="https://www.indulgencemarketing.co.ke">Indulgence Marketing <?php //echo date('Y');?> </a>
          </div>
          <div class="clearfix"></div>
        </footer>
         /footer content -->
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