<?php
//include the init file for all functions
include 'init.php';

//Verifys user logged in 
$USER_ID = $_SESSION['ID'];
	if($USER_ID === null){
		//Show loggin error
	header('Location: login_error.php');
	}else{
		//do nothing
	}
//Get User Details
$User_Data = "SELECT `username` AS Name FROM privileged_users WHERE `ID` = '$USER_ID'"; 
$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
$username = $U_query['Name'];
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Indulgence Marketing Monthly Archives| </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    
    <!-- Custom styling plus plugins -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="home.php" class="site_title"><i class="fa fa-paw"></i> <span>Indulgence Admin</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <img src="images/user.png" alt="..." class="img-circle profile_img">
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
                  <li><a href="home.php"><i class="fa fa-home"></i>Home</span></a>
                    <ul class="nav child_menu">
                    </ul>
                  </li>
                <li><a><i class="fa fa-desktop"></i> Forms <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="Add_Account_Admin.php">Add Account Admin</a></li>
                      <li><a href="form.php">Registration Form</a></li>
                    </ul> 
                  </li> 
				  
				  <li><a><i class="fa fa-edit"></i> Receipts <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="Receipt_Table.php">All Receipts</a></li>
                    </ul> 
                  </li>
				  <li><a> <i class="fa fa-table"></i> Program Members<span class="fa fa-chevron-down"></span></a>
				   <ul class="nav child_menu">
					<li><a href="Members_Table.php?mode=members">Members</a></li>
                      <li><a href="Contacts_Table.php?mode=contacts">Members Contacts</a></li>
                      <li><a href="Loyalty_Table.php?mode=loyalty">Loyalty Funds</a></li>
                      <li><a href="Redemption_Table.php?mode=request">Redemption</a></li>
                      <li><a href="Transactions_Table.php?mode=transactions">Transaction</a></li>
                    </ul> 
				  </li>	
					<li><a href="sales_report.php"><i class="fa fa-lightbulb-o"></i> Sales Report </a>
                    </li>
					<li><a href="archive_files.php"><i class="fa fa-archive"></i>Archived Files</a>
                    </li>
					<li><a><i class="fa fa-desktop"></i> Support <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="support.php?mode=Technical">Technical</a></li>
                      <li><a href="support.php?mode=Operations">Operational</a></li>
                      <li><a href="support.php?mode=Marketing">Marketing</a></li>
                    </ul> 
                  </li>	
				  <li><a href="http://178.62.100.247/interpel/interpelrewards/production/T&C/INTERPEL%20REWARDS%20PROGRAM%20TERMS%20AND%20CONDITIONS.pdf"><i class="fa fa-file-pdf-o"></i> Terms & Conditions</a>
                  </li> 
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

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
            <div class="page-title">
              <div class="title_left">
                <h3>Monthly Program Files <small>Archived</small> </h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">

                    <div class="row">

					<?php
							//Queries customer specific transactions
							$Table_query  = "SELECT * FROM `archived_files`";
							$table_query = mysqli_query(Connect_Database(),$Table_query);

							while($table_data = mysqli_fetch_assoc($table_query)){
							echo '
								<a href="'.$table_data['link'].'"><div class="col-md-55">
									<div class="thumbnail">
									  <div class="image view view-first">
										<img style="width: 100%; display: block;" src="images/CSV.png" alt="image" />
										<a href="'.$table_data['link'].'">
										<div class="mask">
										  <p>'.$table_data['month'].'</p>
										  <div class="tools tools-bottom">
											<a href="'.$table_data['link'].'"><i class="fa fa-eye"></i></a>
										  </div>
										</div>
									  </div>
									  <div class="caption">
										<p>'.$table_data['details'].'</p>
									  </div>
									</div>
								  </div></a>'; 
							}
					?>

					
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
            <a href="https://www.indulgencemarketing.co.ke">Indulgence Marketing 2017 </a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
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