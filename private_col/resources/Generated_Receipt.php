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

		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$redeem_date = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> <i class="fa fa-anchor"> Redemptions Receipt | Interpel CFS<h2>(<?php echo $redeem_date?>)</h2><small><h2> Executed by <Strong><?php echo $username?></Strong></br></br> Signature: ________________________  <small>(for verification of the transaction)</small></h2></small></br> <small>Details:</small> </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-sm">
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
		  	<div class="row">
			
			<!-- First Table -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
				  
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                     <h2>Click on the print button to generate a receipt pdf.</h2>
					 </p>
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
					  
                        <tr>
                          <th>Member ID</th>
                          <th>Company Name</th>
                          <th>Redemption Date</th>
                          <th>Redemption Code</th>
                          <th>Redemption Amount</th>
                          <th>Balance</th>
                        </tr>
                      </thead>

                      <tbody>
<?php
					//Queries customer table values
					$Table_query  = "SELECT * FROM `redemption_receipts` ORDER BY `redemption_receipts`.`redemption_id` DESC LIMIT 1";
					$tablequery = mysqli_query(Connect_Database(),$Table_query);
					while($table_data = mysqli_fetch_assoc($tablequery)){
						echo "<tr>";
						echo "<td>" .$table_data['member_id'] . "</td>" ; 
						echo "<td>" .$table_data['company_name'] . "</td>" ; 		
						echo "<td>" .$table_data['redemption_date'] . "</td>" ; 		
						echo "<td>" .$table_data['redemption_code'] . "</td>" ; 
						echo "<td>" .$table_data['redemption_amount'] . "</td>" ; 
						echo "<td>" .$table_data['balance'] . "</td>" ; 
						echo "</tr>";  
					}
 ?>  

                      </tbody>
				 </table>
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
            <a href="https://www.indulgencemarketing.co.ke">Indulgence Marketing 2016 </a>
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
    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>

	<!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>
	
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <!-- Datatables -->
    <script>	
      $(document).ready(function() {
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            $("#datatable-buttons").DataTable({
              dom: "Bfrtip",
              buttons: [
                {
                  extend: "copy",
                  className: "btn-sm"
                },
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdfHtml5",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              responsive: true
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();

        $('#datatable').dataTable();
		
		$('#datatable').dataTable( {
		       "pageLength": 50
		    } );
		 
        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        var table = $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        TableManageButtons.init();
      });
    </script>
    <!-- /Datatables -->

  </body>
</html>