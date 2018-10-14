<?php
include '../query/php_files/core/init.php';
$USER_ID = $_SESSION['ID'];
	if($USER_ID === null){
		//Show loggin error
	header('Location: ../access/access_error.php');
	
	}else{
		//do nothing
	}
	
//Get User Details
$User_Data = "SELECT `username` AS Name, `phone_no` as number, `email` as email FROM privileged_users WHERE `password` = '$USER_ID'";
$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
$username = $U_query['Name'];
$number = $U_query['number'];
$email = $U_query['email'];

//Get Registered clients
$registered_data = "SELECT COUNT(`member_id`) AS count FROM member WHERE `created_by` = '$username'";
$registered_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$registered_data));
$registered_number = $registered_query['count'];

?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Unique Login Form Widget Responsive, Login form web template,Flat Pricing tables,Flat Drop downs  Sign up Web Templates, Flat Web Templates, Login signup Responsive web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- font files  -->
<link href='//fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
<!-- /font files  -->
<!-- css files -->
<link href="css/animate-custom.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style.css" rel='stylesheet' type='text/css' media="all" />
<!-- /css files -->
</head>
<body style="background-image:url(../images/wall1.jpg);"> 
<br>
<div class="content">	
	<section>				
        <div id="container_demo" >
            <a class="hiddenanchor" id="toregister"></a>
            <a class="hiddenanchor" id="tologin"></a>
            <div id="wrapper">
				<div id="login" class="animate form">
					<div class="content-img">
						<img src="../images/user.png" alt="img" class="w3l-img">
					</div>
                    <form  action="#" autocomplete="on" method="post"style="text_align:center;"> 
                        <h2><?php echo $username;?></h2> 
                        <p> 
                            <label for="emailsignup" class="youmail" data-icon="e" > Your email</label>
                            <input id="emailsignup" name="emailsignup" required="required" type="email" value="<?php if($email){echo $email;}else{echo '---';}?>"/> 
                        </p>
						<p> 
                            <label for="password" class="youpasswd" data-icon="p"> Your Phone Number </label>
							<input id="password" name="password" required="required" type="text" value="<?php if($number){echo $number;}else{echo '---';}?>" /> 
                        </p>
                        <p> 
							<label for="username" class="uname" data-icon="u" > Total Signed Customers </label>
                            <input id="username" name="username" required="required" type="text" value="<?php if($registered_number){echo $registered_number;}else{echo '---';}?>"/>
                        </p>
                        <!--<p class="login button" style="text-align:center;"> 
                            <input type="submit" value="Edit" /> 
						</p>--> 
                        <p class="change_link">
							<a href="../query/php_files/core/logout.php" class="to_register">Log out</a>
						</p>
                    </form>
                </div>
			</div>
        </div>  
    </section>
</div>
	<!-- js -->
	<script type='text/javascript' src='../customer/js/jquery-2.2.3.min.js'></script>	
	<script type='text/javascript' src='../query/script_files/global.js'></script>
	<script type="text/javascript" src="../customer/js/notify.min.js"></script>
	<!-- //js -->
</body>
</html>