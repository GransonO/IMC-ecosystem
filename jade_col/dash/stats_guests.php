<?php
//include the init file for all functions
include '../query/php_files/core/init.php';
$Available_Points = '000';
$Company_name = '';
$Redeemptions_performed = 0;
$Redeemed_points = 0;
$TheID = '';


//REMEBER TO CONFIRM THIS PAGE


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

//Get User Details
$User_Data = "SELECT * FROM privileged_users WHERE `password` = '$USER_ID'"; 
$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
$username = $U_query['username'];
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
	
    <title>Jade Collections | </title>

    <!-- Bootstrap -->
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../query/dist/css/custom.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="../" class="site_title"><i class="fa fa-pagelines"></i> <span>Jade Collections</span></a>
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

				  <li><a href="../dash"><i class="fa fa-home"></i>Home</a></li>
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
				  <li><a href="http://178.62.100.247/interpel/interpelrewards/production/T&C/INTERPEL%20REWARDS%20PROGRAM%20TERMS%20AND%20CONDITIONS.pdf"><i class="fa fa-file-pdf-o"></i> Terms & Conditions</a>
                  </li> 
				  

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
				  <li><a href="http://178.62.100.247/interpel/interpelrewards/production/T&C/INTERPEL%20REWARDS%20PROGRAM%20TERMS%20AND%20CONDITIONS.pdf"><i class="fa fa-file-pdf-o"></i> Terms & Conditions</a>
                  </li> 
				  

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
            <nav>
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
            <div class="page-title">
              <div class="title_left">
                <h3>Top Program Members </h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <ul class="pagination pagination-split">
                          <li><a href="#">A</a></li>
                          <li><a href="#">B</a></li>
                          <li><a href="#">C</a></li>
                          <li><a href="#">D</a></li>
                          <li><a href="#">E</a></li>
                          <li>...</li>
                          <li><a href="#">W</a></li>
                          <li><a href="#">X</a></li>
                          <li><a href="#">Y</a></li>
                          <li><a href="#">Z</a></li>
                        </ul>
                      </div>

                      <div class="clearfix"></div>

        <!-- Element of Interest -->
					  <?php
					
					
//Get top Five Values
$query = "SELECT *
          FROM `member_loyalty_funds`
          ORDER BY `loyalty_value_earned` DESC LIMIT 20;";
$result = mysqli_query(Connect_Database(),$query);
$x=0;
$arrayofrows = array();
while($row = mysqli_fetch_all($result))
{
   $arrayofrows = $row;
}

while($x<20){
$name = $arrayofrows[$x][6];
$id = $arrayofrows[$x][5];

//GET RED COUNT
$firstQuery = "SELECT `redemptions_count` AS red_count,sum(`total_points_redeemed`) as redeemed,sum(`loyalty_value_earned`) as loyalty_earned FROM `member_loyalty_funds` WHERE `member_id` = '$id'"; 
$F_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$firstQuery));
$F_count = number_format($F_query['red_count']);
$sum_points = number_format($F_query['loyalty_earned']);
$redeemed = number_format($F_query['redeemed']);

$members_last_date = "SELECT `max_trans_date` AS member_count FROM `member_loyalty_funds` WHERE `member_id`='$id'";
$last_date_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$members_last_date));
$date_query = $last_date_query['member_count'];

//GET TRANS COUNTS
$firstpur = "SELECT Count(`transaction_amount`) AS red_count,sum(`transaction_amount`) as sum_amount FROM `member_transactions` WHERE `member_id` = '$id'"; 
$Fp_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$firstpur));
$F_pur = number_format($Fp_query['red_count']);
$sum_amount = number_format($Fp_query['sum_amount']);

//GET Email
$Email = "SELECT `email_address` as email,`mobile_number` as phone FROM `member_contact` WHERE `member_id` = '$id'"; 
$Email_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Email));
$email_pur = $Email_query['email'];
$phone_pur = $Email_query['phone'];
   
                       echo ' <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                        <div class="well profile_view">
                          <div class="col-sm-12">
                            <div class="left col-xs-7">
                              <h2>'.$name.'</h2>
                              <ul class="list-unstyled">
                                <li><i class="fa fa-envelope"></i> Email: '.$email_pur.'</li>
                                <li><i class="fa fa-phone"></i> Phone : 0'.$phone_pur.'</li>
                                <li><i class="fa fa-calendar"></i> Joined : '.$date_query.'</li>
                                <li><i class="fa fa-money"></i> Spent Amount : Ksh'.$sum_amount.'</li>
                                <li><i class="fa fa-money"></i> Points Earned : '.$sum_points.'</li>
                                <li><i class="fa fa-spinner"></i> Redeemed Amount : '.$redeemed .'</li>
                                <li><i class="fa fa-star"></i> Transactions : '.$F_pur .'</li>
                                <li><i class="fa fa-trophy"></i> Redemptions : '.$F_count .'</li>
                              </ul>
                            </div>
                            <div class="right col-xs-5 text-center">
                              <img src="../images/user.png" alt="" class="img-circle img-responsive">
                            </div>
                          </div>
                          <div class="col-xs-12 bottom text-center">
                            <div class="col-xs-12 col-sm-6 emphasis">
                              <p class="ratings">
                                <a>4.0</a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star-o"></span></a>
                              </p>
                            </div>
                            <div class="col-xs-12 col-sm-6 emphasis">
                              <a href="eng/mailp.php" class="btn btn-success btn-xs"> <i class="fa fa-user">
                                </i> <i class="fa fa-comments-o"></i> </a>
                              <a href="rpt/profile.php?mode='.$id.'&name='.$name.'" class="btn btn-primary btn-xs">
                                <i class="fa fa-user"> </i> View Profile
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>';
					  $x++;
}
					
					  ?>

        <!-- End of elament -->
 
					</div>
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

    <script src="../query/dist/js/custom.min.js"></script>
  </body>
</html>