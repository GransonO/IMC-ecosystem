<?php
//include the init file for all functions
include '../query/php_files/core/init.php';
$user_id = $_SESSION['ID'];


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
	
    <title>Interpel CFS | </title>

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
              <a href="#" class="site_title"><i class="fa fa-pagelines"></i> <span>Interpel CFS</span></a>
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
                <h3>All Users</h3>
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
$Table_query  = "SELECT `username` as name, `email` as email, `profile` as profile,`created_at` as created_at,`created_by` as created_by FROM `privileged_users`;";
	$tablequery = mysqli_query(Connect_Database(),$Table_query);
	while($result = mysqli_fetch_assoc($tablequery)){
	$myName  = $result['name'];
	$log_query  = "SELECT count(`name`) as name_count, max(`date_time`) as date_time FROM `user_tracker` where `name` = '$myName';";
	$log_result = mysqli_query(Connect_Database(),$log_query);
	$log = mysqli_fetch_assoc($log_result);
   
                       echo ' <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                        <div class="well profile_view">
                          <div class="col-sm-12">
                            <h4 class="brief"><i>'.$result['profile'].'</i></h4>
                            <div class="left col-xs-7">
                              <h2>'.$myName.'</h2>
                              <ul class="list-unstyled">
                                <li><i class="fa fa-envelope"></i> Email: '.$result['email'].'</li>
                                <li><i class="fa fa-calendar"></i> Joined : '.$result['created_at'].'</li>
                                <li><i class="fa fa-user"></i> Invite : '.$result['created_by'].'</li>
                                <li><i class="fa fa-calendar"></i> Last Log : '.$log['date_time'].'</li>
                                <li><i class="fa fa-cog"></i> Logins : '.$log['name_count'].' </li>
                              </ul>
                            </div>
                            <div class="right col-xs-5 text-center">
                              <img src="../images/user.png" alt="" class="img-circle img-responsive">
                            </div>
                          </div>
                          <div class="col-xs-12 bottom text-center">
                            
                            <div class="col-xs-12 col-sm-6 emphasis">
                              <button type="button" class="btn btn-warning btn-xs"> <i class="fa fa-user">
                                </i> <i class="fa fa-comments-o"></i> Edit</button>
                              <button type="button" class="btn btn-danger btn-xs">
                                <i class="fa fa-user"> </i> Delete
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>';
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