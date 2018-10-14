<?php
//include the init file for all functions
include '../query/php_files/core/init.php';
$user_id = $_SESSION['ID'];
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../images/apple-icon.png">
	<link rel="icon" type="image/png" href="../images/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Jade|Redemption</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
	
	<!--     Fonts and icons     -->
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">

	<!-- CSS Files -->
    <link href="../ini/assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../ini/assets/css/gsdk-bootstrap-wizard.css" rel="stylesheet" />
	<!-- This is what you need -->
	<link rel="stylesheet" href="../query/dist/sweetalert.css">

	<!-- CSS Just for demo purpose, please include it in your project -->
	<link href="../ini/assets/css/demo.css" rel="stylesheet" />

</head>

<body>
<div class="image-container set-full-height" style="background-image: url('../images/jade.jpg')">
<div class="image-container set-full-height" style="background: rgba(46,46,46,0.9);">

    <!--   Big container   -->
    <div class="container">
        <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <!--      Wizard container        -->
            <div class="wizard-container">
                <div class="card wizard-card" data-color="green" id="wizard">
                <form action="" method="">
                <!--        You can switch ' data-color="green" '  with one of the next bright colors: "blue", "green", "orange", "red"          -->

                    	<div class="wizard-header">
                        	<h3>
									<div style="text-align:center;">
										<img src="../images/jade_logo.png"  height="70" width="120">
									</div>
                        	   <small>Loyalty Program Management System</small>
                        	</h3>
                    	</div>
						<div class="wizard-navigation">
							<ul>
	                            <li><a href="#profile" data-toggle="tab">Customer Status</a></li>
	                        </ul>
						</div>

                        <div class="tab-content">
						
                            <div class="tab-pane" id="profile">
                              <div class="row">
                                  <div class="col-sm-12">
										<h4 class="info-text"><small> Enter the guests Member ID to retrieve details </small></h4>
                                  </div>
								  
                                  <div class="col-sm-12">
                                      <div class="form-group">
                                          <div class="input-group col-sm-6 col-sm-offset-3">
                                              <input type="text" class="form-control" id="reward_id" placeholder="Member ID">
                                              <span class="input-group-addon"></span>
                                          </div>
                                      </div>
                                  </div>
								  
                                <div class="col-sm-12">
									<div class="col-sm-6 col-sm-offset-3" style="text-align:center;">
										<input type='button' class='btn btn-finish btn-fill btn-default btn-wd btn-sm' id="query_user_submit" name='finish' value='Get Customer account Details' />
									</div><br>
									
										<p><small>.</small></p>
								</div>																		
									<div class="col-sm-12  col-sm-offset-1">
											 <div class="form-group col-sm-5">
											  <div class="input-group">
		                                           <label class="form-control control-label">Customer Name</label>
												  <input type="text" class="form-control" id="reward_name" disabled/>
                                              <span class="input-group-addon"><i class="material-icons"></i></span>
											  </div>
											</div>
											<div class="form-group col-sm-5">
											  <div class="input-group">
		                                           <label class="form-control control-label">Points Available</label>
												  <input type="email" class="form-control" id="points_avail" disabled/>
                                              <span class="input-group-addon"><i class="material-icons"></i></span>
											  </div>
											</div>
											 <div class="form-group col-sm-10">
											  <div class="input-group">
												  <input type="text" class="form-control" id="reward_request" placeholder="Points Requested"/>
                                              <span class="input-group-addon"><i class="material-icons"></i></span>
											  </div>
											</div>
                                  </div>
								  				  
                                <div class="col-sm-6 col-sm-offset-3">
									<div style="text-align:center;">
										<input type='button' class='btn btn-finish btn-fill btn-success btn-wd btn-sm' id="reward_guest_submit" name='finish' value='Redeem' />
                                    <input type='button' class='btn btn-finish btn-fill btn-warning btn-wd btn-sm' id="admin_cancel" name='finish' value='Cancel' />
									</div>
								</div>
                              </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> <!-- wizard container -->
        </div>
        </div> <!-- row -->							
		</div> <!--  big container -->
			<div class="footer">
			   <div class="container">
					 Developed and maintained by 
					 <a href="http://www.indulgencemarketing.co.ke/"> 
						<div class="">
							<img src="../images/logo.png" height="70" width="150">
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</body>

	<!--   Core JS Files   -->
	<script src="../ini/assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
	<script src="../ini/assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../ini/assets/js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="../ini/assets/js/gsdk-bootstrap-wizard.js"></script>

	<!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
	<script src="../ini/assets/js/jquery.validate.min.js"></script>

  <script src="../query/dist/sweetalert-dev.js"></script>
  <script type='text/javascript' src='../query/script_files/func.js'></script>

</html>

