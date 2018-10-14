<?php
include '../query/php_files/core/init.php';
$USER_ID = $_SESSION['ID'];
	if($USER_ID === null){
		//Show loggin error
	header('Location: ../access/access_error.php');
	
	}else{
		//do nothing
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Meta tags -->
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="Angling Booking Form Responsive Widget, Audio and Video players, Login Form Web Template, Flat Pricing Tables, Flat Drop-Downs, Sign-Up Web Templates, Flat Web Templates, Login Sign-up Responsive Web Template, Smartphone Compatible Web Template, Free Web Designs for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design"
	/>
	<script type="application/x-javascript">
		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<!-- Meta tags -->
	<!-- Calendar -->
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<!-- //Calendar -->
	<!--stylesheets-->
	<link href="css/style.css" rel='stylesheet' type='text/css' media="all">
	<!--//style sheet end here-->
	<link href="//fonts.googleapis.com/css?family=Cuprum:400,700" rel="stylesheet">
</head>

<body style="overflow:hidden;">
	<h1 class="header-w3ls">
		CUSTOMER FEEDBACK</h1>
	<div class="appointment-w3">
		<form action="#" method="post">
		
			<div class="form-right-w3ls" style="text-align:center;">
				<input type="text" class="top-up"  id="rew_mobile_ver" name="rew_mobile_ver" placeholder="Customers Phone Number*" required="">
			</div>
			
			<div class="btnn">
					<input type="button" id="rew_mobile_btn" name="rew_mobile_btn" value="GET DETAILS">
				</div>
					<div class="clearfix"></div>
				
			<div class="">
				<div class="form-right-w3ls">

					<input class="buttom" type="text" name="rew_mail" id="rew_mail" placeholder="Customer's Email*" required="">
				</div>
				
				<div class="form-right-w3ls ">

					<input class="buttom" type="text" name="rew_mobile_verified" id="rew_mobile_verified" placeholder="Phone Number" required="">
					<div class="clearfix"></div>
				</div>
			</div>
			
			<div class="form-control-w3l">
				<textarea name="customer_comments" id="customer_comments" placeholder="Customers comments..."></textarea>
			</div><br>
			
			<div class="btnn">
				<input type="button" value="Submit Feedback" id="btn_update_data">
			</div>
		</form>
	</div>
	<!-- js -->
	<script type='text/javascript' src='js/jquery-2.2.3.min.js'></script>	
	<script type='text/javascript' src='../query/script_files/global.js'></script>
	<script type="text/javascript" src="js/notify.min.js"></script>
	<!-- //js -->
</body>

</html>