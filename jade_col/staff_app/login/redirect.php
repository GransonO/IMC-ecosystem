<?php
$mode = $_GET['mode'];
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Flaty User login Form template Responsive, Login form web template,Flat Pricing tables,Flat Drop downs  Sign up Web Templates, Flat Web Templates, Login sign up Responsive web template, SmartPhone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Custom Theme files -->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" /> 
<!-- //Custom Theme files -->
<!-- js -->
<script src="js/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="js/notify.min.js"></script>
<!-- //js -->  
<!-- web font -->
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'><!--web font-->
<!-- //web font -->
</head>
<body>
	<!-- main -->
	<div class="main-agileits">
		
		<h1>Log In</h1>
		<div class="mainw3-agileinfo form">
			<div id="login">    
				<form action="../query/php_files/core/entry_login.php" method="post"> 
					<div class="field-wrap">
						<label> Enter Your Username<span class="req">*</span> </label>
						<input type="text"required autocomplete="on" name="username"/>
					</div> 
					<div class="field-wrap">
						<label> Your Password<span class="req">*</span> </label>
						<input type="password"required autocomplete="off" name="password"/>
					</div> 
					<button class="button button-block" type="submit" />Log In</button> 
					<br>
					<br>
					<p class="forgot" style="text-align:center;"><a href="sign_up.html">Sign Up</a></p> 
				</form> 
			</div>
         
		</div>	
	</div>	
	<!-- //main -->
	<?php 
	if($mode == 'success'){
		echo '<script>
			 $.notify("You have successfully signed up\n You can now log in.","success");
		     </script>';
	}
	?>
	<script>
	$('.form').find('input, textarea').on('keyup blur focus', function (e) { 
	  var $this = $(this),
		  label = $this.prev('label');

		  if (e.type === 'keyup') {
				if ($this.val() === '') {
			  label.removeClass('active highlight');
			} else {
			  label.addClass('active highlight');
			}
		} else if (e.type === 'blur') {
			if( $this.val() === '' ) {
				label.removeClass('active highlight'); 
				} else {
				label.removeClass('highlight');   
				}   
		} else if (e.type === 'focus') {
		  
		  if( $this.val() === '' ) {
				label.removeClass('highlight'); 
				} 
		  else if( $this.val() !== '' ) {
				label.addClass('highlight');
				}
		}
 
	}); 
	</script>
</body>
</html>