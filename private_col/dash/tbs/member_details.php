<?php
//include the init file for all functions
include '../../query/php_files/core/init.php';

$the_mode = $_GET['mode'];
$member_name = $_GET['member_name'];
$email_address = $_GET['email_address'];
$mobile_number = $_GET['mobile_number'];


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

//Gets the date time of redemption
date_default_timezone_set('Africa/Nairobi');
$current_date = date('d/m/Y');

//Queries Accomodation Purchases
$guest_query = "SELECT SUM(`final_total`) AS total_amount FROM `nkl_guests_data` WHERE `guest_name` = '$member_name'";
$acc_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$guest_query));
$guest_format_number = $acc_query['total_amount'];
$guest = number_format($guest_format_number);

//Queries Restaurant Purchases
$restaurant_query = "SELECT SUM(`total_amount`) AS total_amount FROM `nkl_restaurant_details_tbl` WHERE `customer_name` = '$member_name'";
$rest_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$restaurant_query));
$rest_format_number = $rest_query['total_amount'];
$restaurant = number_format($rest_format_number);

//Queries Other Purchases
$other_query = "SELECT SUM(`service_amount`) AS total_amount FROM `nkl_other_services_tbl` WHERE `guest_name` = '$member_name'";
$other_services_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$other_query));
$other_format_number = $other_services_query['total_amount'];
$other = number_format($other_format_number);

$hotel_amt = $guest_format_number - ($rest_format_number + $other_format_number);
$accomodation = number_format($hotel_amt);


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

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="row">
              <div class="col-md-12 col-xs-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Indulgence Marketing | Reports <small>Individual member details </small></h2>
                   <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                          <h1>
                              <img src="../../images/kongoni_logo.png"  height="100" width="200"> <?php echo $member_name;?> Program Details.
                              <small class="pull-right">Date: <?php echo $current_date;?></small>
                          </h1>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- info row -->
                      <div class="row invoice-info col-xs-12 col-md-12 col-sm-12">
                        <div class="col-sm-4 invoice-col">
                          <address>
										<strong>Naivasha Kongoni Lodge.</strong>
                                          <br>P.O.Box: 635-00100 Nairobi, KENYA,
                                          <br>Naivasha, Kenya.
                                          <br>Mobile: +254 774 435628
                                          <br>Email: info@naivashakongonilodge.com
                                       </address>
				                     </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                          <strong>Members Details:</strong>
                          <address>
                          <br><b>Accomodation Purchase :</b>  Ksh <?php echo $accomodation;?>.
                          <br><b>Restaurant Purchase :</b>  Ksh <?php echo  $restaurant;?>.
                          <br><b>Other Transactions:</b>  Ksh <?php echo $other;?>.
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
						<b>Report ID: Ind-<?php echo date('His'); ?>-<?php echo date('dm')-91; ?>-<?php echo date('Y'); ?></b>
                          <br>
                          <br>
                          <b>Total Purchase:</b>  Ksh  <?php echo $guest;?>
                          <br>
                          <b>Total Loyalty:</b>  <?php echo '---';?> Points
                          <br>
                          <b>Requested By :</b>  <?php echo $username;?>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                      <p class="lead">Customers Accomodation Details :</p>
                      <!-- Table row -->
                      <div class="row">					  
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Member ID</th>
                                <th>Booking Source</th>
                                <th>Room Type</th>
                                <th>No of Persons</th>
                                <th>Nights Spent</th>
                                <th>Total Spent</th>
                              </tr>
                            </thead>
                            <tbody>
							<?php 
							
								//Queries customer table values
								$Table_query  = "SELECT * FROM `nkl_guests_data` WHERE `guest_name` = '$member_name';";
								$tablequery = mysqli_query(Connect_Database(),$Table_query);
								while($table_data = mysqli_fetch_assoc($tablequery)){
									echo "<tr>";
									echo "<td>" .$table_data['phone_no'] . "</td>" ; 	
									echo "<td>" .$table_data['source'] . "</td>" ; 	
									echo "<td>" .$table_data['room_type'] . "</td>" ; 	
									echo "<td>" .$table_data['no_of_persons'] . "</td>" ; 	
									echo "<td>" .$table_data['nights_spent'] . "</td>" ; 	
									echo "<td>" .$table_data['final_total'] . "</td>" ; 	
									echo "</tr>";  
								}
							?>
                              </tbody>
                          </table>
                        </div>
                        <!-- /.col -->
											
                      </div>
                      <!-- /.row -->
					  
                      <p class="lead">Customers Restaurant Details :</p>
                      <!-- Table row -->
                      <div class="row">					  
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Receipt Number</th>
                                <th>Transaction Date</th>
                                <th>Transaction Amount</th>
                                <th>Transacted Items</th>
                              </tr>
                            </thead>
                            <tbody>
							<?php 
							
					//Queries customer table values
					$Table_query  = "SELECT * FROM `nkl_restaurant_details_tbl` WHERE `customer_name` = '$member_name';";
					$tablequery = mysqli_query(Connect_Database(),$Table_query);
					while($table_data = mysqli_fetch_assoc($tablequery)){
						echo "<tr>";
						echo "<td>" .$table_data['receipt_no'] . "</td>" ; 	
						echo "<td>" .$table_data['trans_date'] . "</td>" ; 	
						echo "<td>" .$table_data['total_amount'] . "</td>" ; 	
						echo "<td>" .$table_data['restaurant_description'] . "</td>" ; 	
						echo "</tr>";  
					}
						?>
                              </tbody>
                          </table>
                        </div>
                        <!-- /.col -->
											
                      </div>
                      <!-- /.row -->
                      <p class="lead">Customers Other Services Details :</p>
                      <!-- Table row -->
                      <div class="row">					  
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Receipt No</th>
                                <th>Service Date</th>
                                <th>Service Description</th>
                                <th>Service Amount</th>
                              </tr>
                            </thead>
                            <tbody>
							<?php 
							
					//Queries customer table values
					$Table_query  = "SELECT * FROM `nkl_other_services_tbl` WHERE `guest_name` = '$member_name';";
					$tablequery = mysqli_query(Connect_Database(),$Table_query);
					while($table_data = mysqli_fetch_assoc($tablequery)){
						echo "<tr>";
						echo "<td>" .$table_data['receipt_no'] . "</td>" ; 	
						echo "<td>" .$table_data['service_date'] . "</td>" ; 	
						echo "<td>" .$table_data['service_description'] . "</td>" ; 	
						echo "<td>" .$table_data['service_amount'] . "</td>" ; 		
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
                          <img src="../../images/logo.png" alt="indulgencemarketing">   
                          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            A report on the general loyalty program in summary </p>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                          <p class="lead">Member Program Data</p>
                          <div class="table-responsive">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <th style="width:50%">Member Name:</th>
                                  <td><?php echo $member_name ;?></td>
                                </tr>
                                <tr>
                                  <th style="width:50%">Phone Number:</th>
                                  <td><?php echo $mobile_number;?></td>
                                </tr>
                                <tr>
                                  <th>Email Address:</th>
                                  <td><?php echo $email_address;?></td>
                                </tr>
                                <tr>
                                  <th>Member ID:</th>
                                  <td><?php echo $the_mode;?></td>
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
	
  </body>
</html>