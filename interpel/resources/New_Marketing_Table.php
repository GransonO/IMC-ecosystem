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

$recent_data_points = 'Recent points value';
$grouped_data_points = 'Grouped points value';

	
	//Queries latest transaction date
	$Recent_Points  = "SELECT SUM(`points_earned`) AS Sum_Points FROM `recent_data`";
	$Dquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Recent_Points));
	$recent_data_points = $Dquery['Sum_Points'];
	
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TransactionTables | Indulgence</title>

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

	<!-- PNotify -->
    <link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
	
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
				  
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class=" col-md-10 col-sm-10 col-xs-12">
					<form action="" method="post" class="form-horizontal form-label-left">
						  <div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">From Date: <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="date" id="last-name" name="from_date" required="required" class="form-control col-md-7 col-xs-12">
							</div>
						  </div> 
						  
						  <div class="form-group col-md-6 col-sm-6 col-xs-12">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">To Date: <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="date" id="last-name" name="to_date" required="required" class="form-control col-md-7 col-xs-12">
							</div>
						  </div>
					</div>
					
					<div class="col-md-2 col-sm-2 col-xs-12">
                          <button type="submit" class="btn btn-success" name="month_button" value="month_data" >Get data for this month</button>
						  </form> 
					</div>
				</div>
				  
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
						  <th style="color:black;">MEMBER_ID</th>
						  <th style="color:black;">MEMBER NAME</th>
						  <th style="color:black;">EMAIL ADDRESS</th>
						  <th style="color:black;">EXPIRY DATE</th>
						  <th style="color:black;">POINTS EARNED</th>
						  <th style="color:black;">POINTS BALANCE</th>
						  <th style="color:black;">REDEEMED POINTS</th>
						  <th style="color:black;">EXPIRED POINTS</th>
						</tr>
                      </thead>

                      <tbody>
                       <?php
				if(empty($_POST['month_button'])===false){
					
					$from_date = $_POST['from_date'];
					$to_date = $_POST['to_date'];
					
					date_default_timezone_set('Africa/Nairobi');
					$post_date = date('Y-m-d');
					
					$myDate = date('Y-m-d', strtotime('-1 year', strtotime($post_date)) );
						
					//Queries customer table values
					$Table_query  = "SELECT * FROM member_loyalty_funds  WHERE `max_trans_date` > '$myDate'";
					$tablequery = mysqli_query(Connect_Database(),$Table_query);
					while($table_data = mysqli_fetch_assoc($tablequery)){
						
						$member_id = $table_data['member_id'];
						
						//Get future Exipry date
						$startDate = $table_data['max_trans_date'];
						$expiry_date = date('Y-m-d', strtotime('+1 year', strtotime($startDate)) );
						
						//Get members Email ADDRESS 
						$email_query  = "SELECT `email_address`  AS mail FROM member_contact  WHERE `member_id` = '$member_id'";
						$mailquery = mysqli_fetch_assoc(mysqli_query(Connect_Database(),$email_query));
						$email = $mailquery['mail'];
						
						//Queries sum points
						$Recent_Points  = "SELECT SUM(`loyalty_earned`) AS points FROM `member_transactions` WHERE `member_id` = '$member_id' AND `transaction_date` BETWEEN '$from_date' AND '$to_date' ";
						//echo $Recent_Points . '</br>';
						$Dquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Recent_Points));
						$recent_data_points = $Dquery['points'];	
						
						//Queries sum points redeemed
						$Redeemed_Points  = "SELECT SUM(`redeemed_amount`) AS redeemed FROM `member_redemption_transactions` WHERE `member_id` = '$member_id' AND `created_at` BETWEEN '$from_date' AND '$to_date' ";
						$Rquery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Redeemed_Points));
						//echo $Redeemed_Points . '</br>';
						$recent_months_points = $Rquery['redeemed'];
						
						//Queries sum points expired
						$expired_Points  = "SELECT SUM(`expired_points`) AS expired FROM `Points_Expiry` WHERE `member_id` = '$member_id' AND `expiry_date` BETWEEN '$from_date' AND '$to_date' ";
						$Equery =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$expired_Points));
						//echo $expired_Points . '</br>';
						$expired_months_points = $Equery['expired'];
						
						echo "<tr>";
						echo "<td>" .$member_id . "</td>" ; 
						echo "<td>" .$table_data['company_name'] . "</td>" ; 	 		
						echo "<td>" .$email . "</td>" ; 	
						echo "<td>" .$expiry_date . "</td>" ;
						echo "<td>" .$recent_data_points . "</td>" ; 
						echo "<td>" .$table_data['total_points_available'] . "</td>" ; 
						echo "<td>" .$recent_months_points . "</td>" ; 
						echo "<td>" .$expired_months_points . "</td>" ; 
						echo "</tr>";  
					}
				}else
					if(empty($_POST['button_response'])===false){
						
						$customer_id = $_POST['customer_id'];
						$relayed_info = $_POST['message'];
						date_default_timezone_set('Africa/Nairobi');
						$insert_date = date('Y-m-d H:i:s');
						
						$last_post  = "SELECT `response_text` AS last_post FROM `customer_response` WHERE `customer_id` = '$customer_id'";
						$last_post_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$last_post));
						$post_that_was = $last_post_query['last_post'];
						
						$relayed_info = $post_that_was.'\n============='.$insert_date.'=============\n'.$relayed_info.'=======================\n'; 
						
						$RECENT_DATA_INSERT = "INSERT INTO `customer_response` (`date`, `customer_id`, `response_text`)
						VALUES ('$insert_date','$customer_id', '$relayed_info');";
						$INSERT_RESULT = mysqli_query(Connect_Database(),$RECENT_DATA_INSERT);
		
						if($INSERT_RESULT){
							echo '<script> alert(" This is a success! ")</script>';
							
						}else{
							echo '<script> alert("Failed Terribly")</script>';
						}
					}
 ?>  
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
			  			  
			    <div class="x_panel">
                  <div class="x_title">
                    <h2>Customer Detail Form</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <!-- start form for validation -->
                    <form id="demo-form"  action="" method="post" data-parsley-validate>
						  <label for="fullname">Customer ID:</label>
						  <input type="text" id="fullname" class="form-control" name="customer_id" required />
						  </br>
                          <label for="message">Relayed Customers Information</label>
                          <textarea id="message" required="required" class="form-control" name="message" data-parsley-trigger="keyup" data-parsley-minlength="20" data-parsley-maxlength="100" data-parsley-minlength-message="Come on! You need to enter at least a 20 characters long comment.."
                          data-parsley-validation-threshold="10"></textarea>
                          <br/>
                          <button type="submit" class="btn btn-primary" name="button_response" value="customer_response">Post Customer Response</button>
                    </form>
                    <!-- end form for validations -->
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
<?php
   $status_message = "237726nbiyethvb55ycfdtft6535";
   
	if(!$status_message == null){
	switch($status_message){
			
		case '237726nbiyethvb55ycfdtft6535':
			$TopicStatus = 'alert-Error';
            $statusMsgClass = 'error';
            $statusMsg = 'Cant expire, wont expire.... Call Granson !';
            break;
			
        default:
			$TopicStatus = 'Optimum functioning.';
            $statusMsgClass = 'info';
            $statusMsg = 'System functioning under normal perimeters';
		}
		message($TopicStatus,$statusMsg,$statusMsgClass);
	}
			
		function message($title,$text,$type){
			echo "<script>
				$(document).ready(function() {
						new PNotify({
						title: '$title',
						text: '$text',
						type: '$type',
						styling: 'bootstrap3'
					   });

				});
				</script>";
				}
	
	?>
  </body>
</html>