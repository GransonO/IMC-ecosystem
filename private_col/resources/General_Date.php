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

//Get User Details
$User_Data = "SELECT `username` AS Name FROM privileged_users WHERE `ID` = '$USER_ID'"; 
$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
$username = $U_query['Name'];

	if(empty($_POST['is-submitted']) === false){
		$from_date = $_POST['from_date'];
		$to_date = $_POST['to_date'];
	
		header('Location:report.php?mode=General&&from_date='.$from_date.'&&to_date='.$to_date);
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

    <title>Indulgence Admin.Point | </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="../vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
	
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.php" class="site_title"><i class="fa fa-paw"></i> <span>Indulgence Admin.Point | </span></a>
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
                  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">					
                      <li><a href="home.php">Home </a></li>
                    </ul>
                  </li>
                <li><a><i class="fa fa-desktop"></i> Forms <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="Add_Account_Admin.php">Add Account Admin</a></li>
                      <li><a href="form.php">Registration Form</a></li>
                    </ul> 
                  </li> 
                <li><a><i class="fa fa-keyboard-o"></i> Transactions <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="purchase_transactions.php">Purchase Transactions</a></li>
                      <li><a href="loyalty_transactions.php">Loyalty  Transactions</a></li>
                    </ul> 
                  </li> 
			  <li><a><i class="fa fa-bell-o"></i>Engagement Platforms <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="sms_platform.php">SMS</a></li>
                      <li><a href="mail_platform.php">EMAIL</a></li>
                    </ul> 
                  </li>
				  <li><a><i class="fa fa-edit"></i> Receipts <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="Receipt_Table.php">All Receipts</a></li>
                    </ul> 
                  </li>
				  <li><a> <i class="fa fa-table"></i> Program Data Tables<span class="fa fa-chevron-down"></span></a>
				   <ul class="nav child_menu">
					<li><a href="Members_Table.php?mode=members">Members Table</a></li>
                      <li><a href="Contacts_Table.php?mode=contacts">Members Contacts Table</a></li>
                      <li><a href="Loyalty_Table.php?mode=loyalty">Loyalty Funds Table</a></li>
                      <li><a href="Redemption_Table.php?mode=request">Redemption Table</a></li>
                      <li><a href="Transactions_Table.php?mode=transactions">Transaction Table</a></li>
                    </ul> 
				  </li>	
				  <li><a><i class="fa fa-file-text-o"></i> Reports <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="report.php?mode=General">General Reports</a></li>
                      <li><a href="individual_property.php?mode=Individual And Timed">Individual/timed Reports</a></li>
                    </ul> 
                  </li>
					  <li><a><i class="fa fa-desktop"></i> Support <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="support.php?mode=Technical">Technical</a></li>
                      <li><a href="support.php?mode=Operational">Operational</a></li>
                      <li><a href="support.php?mode=Marketing">Marketing</a></li>
                    </ul> 
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
              </div>

            </div>
			
            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">

				  <!-- Smart Wizard -->
                    <p>Enter the date values to generate the report</p>
                    <div id="wizard" class="form_wizard wizard_horizontal">
                      <div id="step-1">      
	  <!--Start of step 1--->
	   <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                  <div class="x_content">

			</br>
			</br>
			<!--Timed start here-->
			  <div class="col-md-12 col-sm-12 col-xs-12" >
              <div class="">
                <div class="x_title">
                  <h1 style="text-align: center;"> Generate General report</h1>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
				
                    <form action="" method="post" class="form-horizontal form-label-left">
			
				<div class="col-md-3 col-sm-12 col-xs-12"><p>.</p></div>
				<div class="col-md-6 col-sm-12 col-xs-12" style="background:#def1ff;">
                      <p> 
					  					  
					  <div class="form-group">
                       <div class="col-md-12 col-sm-12 col-xs-12">
					   <label>From date:</label>
							<div class="col-md-12 col-sm-12 col-xs-12 form-group pull-right top_search">
							  <div class="input-group">
									<input type="date" name="from_date" class="form-control" placeholder="Search for..." required="required">
										<span class="input-group-btn">
										  <button class="btn btn-default" type="button">.</button>
									  </span>
									</div>
								</div>
							</div>
						</br>
                      </div> 
					  
					 <div class="form-group">
                       <div class="col-md-12 col-sm-12 col-xs-12">
					   <label>To date:</label>
							<div class="col-md-12 col-sm-12 col-xs-12 form-group pull-right top_search">
							  <div class="input-group">
									<input type="date" name="to_date" class="form-control" placeholder="Search for..." required="required">
										<span class="input-group-btn">
										  <button class="btn btn-default" type="button">.</button>
									  </span>
									</div>
								</div>
							</div>
						</br>
                      </div>
					  
					  	<div class="form-group"  style="text-align: center;">
                          <button type="submit" class="btn btn-success" name="is-submitted" value="GO" >Submit</button>
                          <button type="submit" value="cancel" class="btn btn-danger" onClick="window.location='home.php';">Cancel</button>
						</div>
                      </div>  </p>
                <div class="col-md-3 col-sm-12 col-xs-12"><p>.</p></div>
				</br>			  
				     
                    </form>      
					
                  </div>
                </div>
              </div>
			<!--End here-->
				  
				  </div>
                </div>
              </div>
            </div>
<!--End of step 1--->
        </div>
		</div>
             <!-- End SmartWizard Content -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
		<div class="clearfix"></div>
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
    <!-- jQuery Smart Wizard -->
    <script src="../vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
   <!-- jQuery Tags Input -->
    <script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="../vendors/autosize/dist/autosize.min.js"></script>

    <!-- /jQuery Smart Wizard -->
	    <!-- Select2 -->
    <script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Select a state",
          allowClear: true
        });
        $(".select2_group").select2({});
        $(".select2_multiple").select2({
          maximumSelectionLength: 4,
          placeholder: "Max subjects limit",
          allowClear: true
        });
      });
    </script>
    <!-- /Select2 -->
  </body>
</html>