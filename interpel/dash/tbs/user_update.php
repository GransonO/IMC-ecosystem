<?php
//include the init file for all functions
include '../../query/php_files/core/init.php';
$user_id = $_SESSION['ID'];
	if($user_id === null){
		//Show loggin error
	header('Location: ../../ini/login_error.html');
	
	}else{
		//do nothing
	}
$member_id = $_GET['mode'];
$get_details = "SELECT * FROM `member_contact` WHERE `member_id` = '$member_id'";
$result_details = mysqli_fetch_assoc(mysqli_query(Connect_Database(),$get_details));
$company_name = $result_details['company_name'];
$poc_name = $result_details['poc_name'];
$mobile_number = $result_details['mobile_number'];
$email_address = $result_details['email_address'];
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../../images/apple-icon.png">
	<link rel="icon" type="image/png" href="../../images/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Interpel | Update Customer</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
	
	<!--     Fonts and icons     -->
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">

	<!-- CSS Files -->
    <link href="../../ini/assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../../ini/assets/css/gsdk-bootstrap-wizard.css" rel="stylesheet" />
	<!-- This is what you need -->
	<link rel="stylesheet" href="../../query/dist/sweetalert.css">

	<!-- CSS Just for demo purpose, please include it in your project -->
	<link href="../../ini/assets/css/demo.css" rel="stylesheet" />

</head>

<body>
<div class="image-container set-full-height" style="background-image: url('../../images/interpel_back.jpg')">
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
									<img src="../../images/interpel.png"  height="80" width="160">
								</div>
                        	</h3>
                    	</div>
								<div class="wizard-navigation">
									<ul>
			                            <li><a href="#address" data-toggle="tab">Update Customer Account</a></li>
			                        </ul>
								</div>

		                        <div class="tab-content">
		                        								
		                            <div class="tab-pane" id="address">
		                                <div class="row">
		                                  					  
                                  <div class="col-sm-12">
                                      <div class="form-group">
                                          <div class="input-group col-sm-6 col-sm-offset-3">
                                              <input type="text" class="form-control" id="reward_id" placeholder="Member ID" value="<?php echo $member_id;?>" disabled>
                                              <span class="input-group-addon"></span>
                                          </div>
                                      </div>
                                  </div>
								  
                                <div class="col-sm-12">
									<div class="col-sm-6 col-sm-offset-3" style="text-align:center;">
										<p style="color:gray;">The Member ID cannot be editted <br><small>Contact IMC IT for assistance</small><p>
									</div>
									
										<h4 class="info-text" style="text-align:center;"><small>.</small></h4>
								</div>	
											
		                                    <div class="col-sm-5 col-sm-offset-1">
		                                         <div class="form-group label-floating">
		                                            <input type="text" id="update_company_name" class="form-control" placeholder="Company Name" value="<?php echo $company_name;?>" >
		                                        </div>
		                                    </div>
		                                    <div class="col-sm-5">
	                                        	<div class="form-group label-floating">
	                                    			<input type="text" id="update_poc_name" class="form-control" placeholder="Person Of Contact" value="<?php echo $poc_name;?>" >
	                                        	</div>
		                                    </div>
											
		                                    <div class="col-sm-5 col-sm-offset-1">
		                                        <div class="form-group label-floating">
		                                            <input type="text" id="update_phone_number" class="form-control" placeholder="Phone Number" value="<?php echo $mobile_number;?>" >
		                                        </div>
		                                    </div>
		                                    <div class="col-sm-5">
		                                        <div class="form-group label-floating">
	                                            	<input type="email" id="update_email_address" class="form-control" placeholder="Email Address" value="<?php echo $email_address;?>" >
	                                            	</select>
		                                        </div>
		                                    </div>
											
									  <div style="text-align:center;">
                                    <input type='button' class='btn btn-finish btn-fill btn-info btn-wd btn-sm' id="update_member_submit" name='finish' value='Update Details' />
									<input type='button' class='btn btn-finish btn-fill btn-warning btn-wd btn-sm' id='admin_cancel' name='finish' value='Cancel'/>
									</div>
		                                </div>
		                            </div>
		                        </div>
		                       <div class="wizard-footer">
                            	<div class="pull-right">
                                    <input type='button' class='btn btn-next btn-fill btn-success btn-wd btn-sm' name='next' value='Next' />

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
							<img src="../../images/logo.png" height="70" width="150">
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</body>

	<!--   Core JS Files   -->
	<script src="../../ini/assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
	<script src="../../ini/assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../../ini/assets/js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="../../ini/assets/js/gsdk-bootstrap-wizard.js"></script>

	<!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
	<script src="../../ini/assets/js/jquery.validate.min.js"></script>

  <script src="../../query/dist/sweetalert-dev.js"></script>
  <script type='text/javascript' src='../../query/script_files/func.js'></script>

</html>

