<?php
//include the init file for all functions
include 'init.php'; 
$Assigned_ID = '';

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

    <title>Indulgence Dashboard</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
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

	
	<!-- PNotify -->
    <link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
		
    <!-- Custom Theme Style -->
	
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
	<script type="text/javascript">
		function ID_Exists(){
		alert("This ID cannot be used as it already exist in the database");
	  };  
	  function ID_ExistsT(){
		alert("Double entry error. This ID is already in use");
	  };	  
	function ID_Doesnt_Exists(){
		alert("This ID can be used");
	  };
	  function Data_Created(){
		alert("The new Entry has been added successfully.");
	  };
	  function wrong_format(){
		alert("The Identifier used doues not conform to the required format.");
	  };
    </script>
  </head>

  <body class="nav-md">
  
  	    <!-- Custom Notification -->

	<?php
	
	if(empty($_POST['verify']) === false){
		$TheID = $_POST['verify'];
		//Get ID Details
	if(strlen($TheID) === 8){
	$User_ID = "SELECT `member_id` AS ID FROM member WHERE `member_id` = '$TheID'"; 
	$ID_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_ID));
	$result = ($ID_query['ID'])? $ID_query['ID']: 0; 

	if($result === 0){
		//If the ID doesnt Exist
		echo '<script type="text/javascript"> ID_Doesnt_Exists(); </script>';	
		//If the ID doesnt Exist
		$Assigned_ID = $TheID;
		//echo  '<script>
		//	$(document).ready(function() {
		//			new PNotify({
		//			title: \'Confirmation Success!\',
		//			text: \'You can resume with the registration.\',
		//			type: \'success\',
		//			styling: \'bootstrap3\'
		//		   });

		//	});
		//	</script>';
		}else{
		//If the ID does Exist
		echo '<script type="text/javascript"> ID_Exists(); </script>';
			//If the ID does Exist
		//	echo '<script>
		//	$(document).ready(function() {
		//			new PNotify({
		//			title: \'Registration ID error\',
		//			text: \'The Member ID is already in use. Please use a different ID.\',
		//			type: \'info\',
		//			styling: \'bootstrap3\'
		//		   });

		//	});
		//	</script>';
			}
		}else{
		//If the ID does Exist
		echo '<script type="text/javascript"> wrong_format(); </script>';
		//		echo  '<script>
		//		$(document).ready(function() {
		//			new PNotify({
		//			title: \'Format Error!\',
		//			text: \'The Identifier used doues not conform to the required format.\',
		//			type: \'error\',
		//			styling: \'bootstrap3\'
		//		   });

		//	});
		//	</script>';
		}
	}
	
	// Second Query
		if(empty($_POST['company_name']) === false){
		$company_name = $_POST['company_name'];
		$person_of_contact = $_POST['person_of_contact'];
		$phone_number = $_POST['phone_number'];
		$email_address = $_POST['email_address'];
		$confirm_id = $_POST['confirm_id'];
	
	$G_ID = "SELECT `member_id` AS ID FROM member WHERE `member_id` = '$confirm_id'"; 
	$ID_G =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$G_ID));
	$Gresult = ($ID_G['ID'])? $ID_G['ID']: 0; 
	
		if($Gresult === 0){
		 $_SESSION['company_name'] = $company_name;
		 $_SESSION['person_of_contact'] = $person_of_contact;
		 $_SESSION['phone_number'] = $phone_number;
		 $_SESSION['email_address'] = $email_address;
		 $_SESSION['confirm_id'] = $confirm_id;
		
		// send data to the next page
		header('Location: new_entry_qualifier.php');
		}
		else{
		//If the ID Exist
		 $_SESSION['confirm_id'] = $confirm_id;
		header('Location: entry_fail.php');
		}
	}
     ?>
  
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

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a href="home.php"><i class="fa fa-home"></i> Home</span></a>
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
                <h3>Enter Members Identifier</h3>
				<h2><?php echo 'The assigned User ID is:<h3> '. $Assigned_ID; '</h3>'?></h2>
              </div>

              <div class="title_right">
                <div class="col-md-6 col-sm- col-xs-12 form-group pull-right top_search">
				<form action="" method="post">
				 <div class="input-group">
                    <input type="text" class="form-control" placeholder="Check for identifier availability" name="verify" required="required">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="submit">Go!</button>
                    </span>
                  </div>
				   </form>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>New Member Registration</h2>
					 <ul class="nav navbar-right panel_toolbox">
                     </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                   
					<!--Registration Design-->
                    <form action="" method="post" class="form-horizontal form-label-left">
			
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Confirmed ID<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id = "confirm" name="confirm_id" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Company Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="company_name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Person Of Contact <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name" name="person_of_contact" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>  
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Phone No <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="number" id="last-name" name="phone_number" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>          
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Email <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="email" id="last-name" name="email_address" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                 					  
				     <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 ">
                          <button type="submit" class="btn btn-success">Submit</button>
                          <button type="submit" name="submitted" value="cancel" class="btn btn-primary" onClick="window.location='home.php';">Cancel</button>
                        </div>
                      </div>
                    </form>      
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

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../vendors/google-code-prettify/src/prettify.js"></script>
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
    <!-- jQuery autocomplete -->
    <script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- starrr -->
    <script src="../vendors/starrr/dist/starrr.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
	
		<!-- PNotify -->
    <script src="../vendors/pnotify/dist/pnotify.js"></script>
    <script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>
	
	 
  </body>
</html>