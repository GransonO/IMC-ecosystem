<?php
//include the init file for all functions
include 'init.php';


$the_password = $_GET['pass'];
$the_user = $_GET['user'];
$the_mail = $_GET['mail'];

	if(empty($_POST['new_pass']) === false){
		
		if($_POST['new_pass'] === $_POST['confirm_pass'] ){
			
		$old_pass =md5($the_password);
		$new_pass = $_POST['new_pass'];
			
		$user =$the_user;
		$password = md5($new_pass);
		$update_query = "UPDATE `privileged_users` SET `password` = '$password' WHERE `username` = '$user' AND `password` = '$old_pass'";
		$update_pass = (mysqli_query(Connect_Database(),$update_query))? 1:0;
	
		if($update_pass === 1){
		//Your new password has been saved succuessfully and sent to your mail for future accessibility
		header('Location: reset_success.php');
		
		}else{
		//
		header('Location: reset_error.php');
		}		
	}else{
			
		header('Location: match_error.php');
	}
	
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

    <title>Indulgence Admin.Point | </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://colorlib.com/polygon/gentelella/css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
		  
            <!-- Does the login  -->
            <form action="" method="post">
              <h1>Change Password</h1>
			  
                  <p>Enter your new password and confirm.</p>
              <div>
			    <input type="text" class="form-control" name="new_pass"		placeholder="New Password" 	   required="required"/>
                <input type="text" class="form-control" name="confirm_pass" placeholder="Confirm Password" required="required"/>
				<button type="submit" class="btn btn-default submit">Execute</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <div>
                  <h1><i class=""></i> Indulgence Admin.Password Point!</h1>
                  <p>©2016 All Rights Reserved |. Indulgence Marketing .</br>Terms and Conditions apply</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>