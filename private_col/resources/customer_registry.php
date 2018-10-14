<?php
//include the init file for all functions
include 'init.php';
$the_name = $_GET['name']; 

	//Gets the date time of redemption
	date_default_timezone_set('Africa/Nairobi');
	$create_date = date('Y-m-d H:i:s');
		
	if($_POST['password'] === $_POST['con_password'] ){
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			$_SESSION['NAME'] = $_POST['username'];
			$_SESSION['PASS'] = $_POST['password'];
			
			$le_pass = md5($password);
			$email = $_POST['mail'];
			
			$insert_into_users = "UPDATE `member_contact` SET `password` = '$le_pass' WHERE
			`member_id` = '$the_name';";
			
			$zeal = mysqli_query(Connect_Database(), $insert_into_users)? 1:0 ;
			
		//	echo $insert_into_users."</br>";
		//	echo $zeal."</br>";
		if($zeal === 1){
				header('Location: customer_registry_success.php');
		}else{
			header('Location: registry_error.php');
		}
		
		}else{
			header('Location: match_error.php');
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
              <h1>Client Portal Registry</h1>			  
                  <p>Fill all entries as required</p>
              <div>
			    <input type="text" class="form-control" name="username"		placeholder="Username" required="required"/>
			    <input type="text" class="form-control" name="mail"		placeholder="Email" required="required"/>
			    <input type="password" class="form-control" name="password"		placeholder="Password" required="required"/>
			    <input type="password" class="form-control" name="con_password"		placeholder="Confirm password" required="required"/>
				
				<button type="submit" name="submitted" class="btn btn-success submit">Submit</button>
                <button type="submit"  value="cancel" class="btn btn-primary" onClick="window.location='index.php';">Cancel</button>
              </div>
              <div class="clearfix"></div>
              <div class="separator">
                <div class="clearfix"></div>
                <div>
                  <p>©2016 All Rights Reserved |. Interpel Investments .</br>Terms and Conditions apply</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>