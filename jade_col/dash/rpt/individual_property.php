<?php
//include the init file for all functions
include '../../query/php_files/core/init.php';
	
$com_name = '';
$generatee = '';
$mem_id = '';
//Verify user logged in 
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

	if(empty($_POST['verify']) === false){
		$TheID = $_POST['verify'];
		//Get ID Details
	$User_ID = "SELECT `member_id` AS ID, `company_name` AS com FROM member WHERE `member_id` = '$TheID'"; 
	$ID_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_ID));
	$result = ($ID_query['ID'])? $ID_query['ID']: 0; 
	$com_name = trim($ID_query['com']);

	if($result === 0){
		//If the ID doesnt Exist
		$generatee = 'The Identifier entered cannot be traced in the database.';
	}else{
		//If the ID does Exist		
		 $_SESSION['indi'] = trim($result);
		 $_SESSION['indiNa'] = trim($com_name);
		
		$mem_id = trim($result);
		$generatee = 'Generating report for '.$com_name;
		}	
		
	}
	
	if(empty($_POST['is-submitted']) === false){
		$TheID = $_SESSION['indi'];
		$member_name = $_SESSION['indiNa'];
		$from_date = $_POST['from_date'];
		$to_date = $_POST['to_date'];
		
		header('Location:individual_report.php?mode='.$TheID.'&&name='.$member_name.'&&from_date='.$from_date.'&&to_date='.$to_date);
	}
	
	if(empty($_POST['time-submitted']) === false){
		
		header('Location:report.php');
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
    <!-- NProgress -->
    <link href="../../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../../../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="../../../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="../../../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../../../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="../../../vendors/starrr/dist/starrr.css" rel="stylesheet">
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
                <h3>Select Individual Properties to Preview</h3>
              </div>

              <div class="title_right">
                <div class="col-md-8 col-sm-12 col-xs-12 form-group pull-right top_search">                 				
				<form action="" method="post" name = "XX2" class="form-horizontal form-label-left"> 
						<div class="input-group top_search" >
						<input type="text" class="form-control" placeholder="Enter Members Identifier" name="verify" required="required">
						<span class="input-group-btn">
						<button class="btn btn-default" type="submit">search member</button>
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
                  <div class="x_content">

				  <!-- Smart Wizard -->
                    <p>Once done click on submit to generate report.</p>
                    <div id="wizard" class="form_wizard wizard_horizontal">
                      <div id="step-1">      
	  <!--Start of step 1--->
	   <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                  <div class="x_content">
                    <br />
 		  <!--Individual start here-->
			  <div class="col-md-12 col-sm-12 col-xs-12"  style="background:#d8fae1;">
              <div class="">
                <div class="x_title">
                  <h1 style="text-align: center;"> <?php echo $generatee; ?> </h1>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="" method="post" class="form-horizontal form-label-left">
			
					<div class="col-md-6 col-sm-12 col-xs-12"  style="background:#d8fae1;">
                      <div class="form-group">  						
					   <label>Member ID:</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <input type="text" placeholder = "Member ID" id = "confirm" name="member_id" required="required" class="form-control col-md-7 col-xs-12" disabled value="<?php echo $mem_id;?>">
                        </div>
                      </div>
					  </br>
					  <div class="form-group">					  
					   <label>Member Name:</label>
                       <div class="col-md-12 col-sm-12 col-xs-12">
                          <input type="text" name="member_name" required="required" placeholder="Member Name" class="form-control col-md-7 col-xs-12" value="<?php echo $com_name;?>" disabled >
                        </div>
                      </div>
					  </br>
					  </div>
					<div class="col-md-6 col-sm-12 col-xs-12"  style="background:#d8fae1;">
                      <p> 
							<div class="form-group">
									   <div class="col-md-12 col-sm-12 col-xs-12">
									   <label>From date:</label>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group pull-right top_search">
								  <div class="input-group">
										<input type="date" name="from_date" class="form-control" placeholder="Search for..."  required="required" >
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
										<input type="date" name="to_date" class="form-control" placeholder="Search for..."  required="required" >
											<span class="input-group-btn">
											  <button class="btn btn-default" type="button">.</button>
										  </span>
										</div>
									</div>
								</div>
										</br>
						 </div>

                      </div>  </p>
                 	</br>			  
				     <div class="form-group"  style="text-align: center;">
                          <button type="submit" class="btn btn-success" name="is-submitted" value="GO" >Submit</button>
                          <button type="submit" value="cancel" class="btn btn-danger" onClick="window.location='home.php';">Cancel</button>
                        </div>
                    </form>      
					
                  </div>
                </div>
              </div>
			  
			<!--End here-->
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
    <script src="../../../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../../../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../../../vendors/nprogress/nprogress.js"></script>
    <!-- jQuery Smart Wizard -->
    <script src="../../../vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../../query/dist/js/custom.min.js"></script>
   <!-- jQuery Tags Input -->
    <script src="../../../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="../../../vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../../../vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="../../../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="../../../vendors/autosize/dist/autosize.min.js"></script>

  </body>
</html>