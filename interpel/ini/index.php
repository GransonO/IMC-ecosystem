<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../images//apple-icon.png">
	<link rel="icon" type="image/png" href="../images//favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Interpel | Login</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
	
	<!--     Fonts and icons     -->
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">

	<!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/gsdk-bootstrap-wizard.css" rel="stylesheet" />
	<!-- This is what you need -->
	<link rel="stylesheet" href="../query/dist/sweetalert.css">

	<!-- CSS Just for demo purpose, please include it in your project -->
	<link href="assets/css/demo.css" rel="stylesheet" />

</head>

<body>
<div class="image-container set-full-height" style="background-image: url('../images/interpel_back.jpg')">
<div class="image-container set-full-height" style="background: rgba(46,46,46,0.9);">

    <!--   Big container   -->
    <div class="container">
        <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <!--      Wizard container        -->
            <div class="wizard-container">
                <div class="card wizard-card" data-color="blue" id="wizard">
                <form action="" method="">
                <!--        You can switch ' data-color="green" '  with one of the next bright colors: "blue", "green", "orange", "red"          -->

                    	<div class="wizard-header">
                        	<h3>
                        	   <div style="text-align:center;">
										<img src="../images/interpel.png"  height="70" width="150">
									</div>
								<small>Loyalty Program Management System</small>
                        	</h3>
                    	</div>
						<div class="wizard-navigation">
							<ul>
	                            <li><a href="#profile" data-toggle="tab">Profile</a></li>
	                            <li><a href="#sign_in" data-toggle="tab">Sign In</a></li>
	                        </ul>
						</div>

                        <div class="tab-content">
						
                            <div class="tab-pane" id="profile">
                                <h4 class="info-text">Select The profile you want to log into. </h4>
                                <div class="row">
                                    <div class="col-sm-12">									
									
                                        <div class="col-sm-2 col-sm-offset-0">
                                            <div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Access Management and Finance accounts." style="text-align:center;">
                                                <input type="radio" name="type" value="House">
                                                <div class="icon">
		                                          <i><img src="../images/management.png" width="75" height="75" onclick='myProfile("MANAGEMENT"); return false;'></i>
                                                </div>
                                                <h6 >Management</h6>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-sm-offset-1">
                                            <div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Access your Marketing Account.">
                                                <input type="radio" name="type" value="Appartment">
                                                <div class="icon">
		                                          <i><img src="../images/marketing.png" width="75" height="75" onclick='myProfile("MARKETING"); return false;'></i>
                                                </div>
                                                <h6>Marketing</h6>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-sm-offset-1">
                                            <div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Access the Accounting account.">
                                                <input type="radio" name="type" value="Apartment">
                                                <div class="icon">
		                                          <i><img src="../images/accounting.png" width="75" height="75" onclick='myProfile("ACCOUNTING"); return false;'></i>
                                                </div>
                                                <h6>Accounts</h6>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-sm-offset-1">
                                            <div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Access the IT and Support Accounts">
                                                <input type="radio" name="type" value="Apartment">
                                                <div class="icon">
		                                          <i><img src="../images/it.png" width="75" height="75" onclick='myProfile("IT"); return false;'></i>
                                                </div>
                                                <h6>IT Support</h6>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="sign_in">
                              <div class="row">
                                  <div class="col-sm-12">
                                    <h4 class="info-text"> Provide your details to access your account </h4>
                                  </div>
								  
                                  <div class="col-sm-6  col-sm-offset-3">
                                      <div class="form-group">
                                          <div class="input-group">
                                              <input type="text" class="form-control" id="login_username" placeholder="Username">
                                              <span class="input-group-addon"><i class="material-icons">face</i></span>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <div class="input-group">
                                              <input type="password" class="form-control" id="login_pass" placeholder="Password">
                                              <span class="input-group-addon"><i class="material-icons">lock</i></span>
                                          </div>
                                      </div>
									  <div class="pull-right">
                                    <input type='button' class='btn btn-finish btn-fill btn-info btn-wd btn-sm' id="log_me_in_btn" name='finish' value='Login' />
									<br>
									<h6><a href="../dash/recov/"><small>Forgot Password?</small></a> <small><p id="profile_txt" style="opacity: 0.0;">indulgence</p></small></h6>
                                </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="wizard-footer">
                            	<div class="pull-right">
                                    <input type='button' class='btn btn-next btn-fill btn-warning btn-wd btn-sm' name='next' value='Next' />

                                </div>
                                <div class="pull-left">
                                    <input type='button' class='btn btn-previous btn-fill btn-default btn-wd btn-sm' name='previous' value='Previous' />
                                </div>
                                <div class="clearfix"></div>
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
						<img src="../images/logo.png" height="60" width="120">
					</div>
				</a>
			</div>
		</div>
	</div>
</div>
<script>
		function myProfile(profile){
				document.getElementById("profile_txt").innerHTML = profile;
	
			}	
</script>
</body>

	<!--   Core JS Files   -->
	<script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="assets/js/gsdk-bootstrap-wizard.js"></script>

	<!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
	<script src="assets/js/jquery.validate.min.js"></script>

  <script src="../query/dist/sweetalert-dev.js"></script>
  <script type='text/javascript' src='../query/script_files/func.js'></script>

</html>

