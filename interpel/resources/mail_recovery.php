<?php
//include the init file for all functions
include 'init.php';

	if(empty($_POST['email']) === false){
		$TheEmail = $_POST['email'];
		$TheUsername = $_POST['username'];
	$get_recovery = "SELECT `username` AS NAME,`ID` AS Id,`email` AS Email FROM `privileged_users` WHERE `username` = '$TheUsername' AND `email` = '$TheEmail'"; 
	$recovery_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$get_recovery));
	$recovery_Name = $recovery_query['NAME']; 
	$recovery_Id = $recovery_query['Id']; 
	$recovery_Mail = $recovery_query['Email']; 
	
	$recovery_result = ($recovery_query['NAME'])? 1: 0; 
		
	if($recovery_result == 1){
		//Call the mailing function
		recover($recovery_Id,$recovery_Mail,$TheUsername);
		}else{
		header('Location: recovery_error.php');
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

    <title>Indulgence Recovery |</title>

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
              <h1> Password Recovery </h1>
              <div>
			    <input type="text" class="form-control" name="username" placeholder="Username" required="required" />
                <input type="email" class="form-control" name="email" placeholder="email" required="required" />
				<button type="submit" class="btn btn-default submit">Submit</button>
                <button type="submit" name="submitted" value="cancel" class="btn btn-default" onClick="window.location='index.php';">Cancel</button>
              </div>
              <div class="clearfix"></div>
                <div class="clearfix"></div>
                <div>
                  <h1><i class=""></i> Indulgence Admin. Recovery Point!</h1>
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