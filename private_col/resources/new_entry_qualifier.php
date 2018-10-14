<?php
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
	
		$company_name = $_SESSION['company_name'];
		$person_of_contact = $_SESSION['person_of_contact'];
		$phone_number = $_SESSION['phone_number'];
		$email_address = $_SESSION['email_address'];
		$confirm_id = $_SESSION['confirm_id'];
	
	if (empty($_POST['submitted']) === false && $_POST['submitted'] === 'DONE'){
			
	//Gets the date time of entry
	date_default_timezone_set('Africa/Nairobi');
	$Entry_date = date('Y-m-d H:i:s');	

		//If the ID doesnt Exist Insert Data to member table 
		$insert_into_member = "INSERT INTO `member` (`created_at`, `created_by`,`member_id`, `company_name`, `date_deleted`)
		VALUES ('$Entry_date','$username', '$confirm_id', '$company_name', '2222-12-12');";		
		
		$insert_into_member_contact = "INSERT INTO `member_contact` (`created_at`, `created_by`,`member_id`, `company_name`, `poc_name`, `poc_is_changed`, `mobile_number`, `email_address`)
		VALUES ('$Entry_date','$username', '$confirm_id','$company_name', '$person_of_contact', '0', '$phone_number', '$email_address')";
		
		//inserts into  table 
		if (mysqli_query(Connect_Database(), $insert_into_member) && mysqli_query(Connect_Database(), $insert_into_member_contact)) {
			
			header('Location: entry_success.php');
			
		} else {
			die('Could not enter data: ' . mysqli_error(Connect_Database()));
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

    <title>Indulgence Point | </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md" style="background-color:hsla(30,100%,70%,0.7);">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number">Performing Action:</h1>
			  <p><h2>Addition of a new member to the program.</h2></p>
			  
              <h2>Proposed Member Identification Key: </h2>
              <h1> <?php echo $confirm_id; ?> </h1>
			  
              <h2>Registering Company's name: </h2>		
              <h1> <?php echo $company_name; ?> </h1>
			  
              <h2>Chief person of contact: </h2>
              <h1> <?php echo $person_of_contact; ?> </h1>
			  
              <h2>Registered phone number: </h2>
              <h1> <?php echo $phone_number; ?> </h1>
			  
              <h2>Registered email address: </h2>
              <h1> <?php echo $email_address; ?> </h1>
			  
              <p><a href="#">Confirm the details and approve to complete the operation </a>
              </p>
              <div class="mid_center">
			  <form action="" method="post">
				<div class="form-group">
					<div class="col-md-12 col-sm-12 col-xs-12 "  style="text-align: center;">
                          <button type="submit" name="submitted" value="DONE" class="btn btn-success">Approve</button>
                          <button type="submit" value="cancel" class="btn btn-primary" onClick="window.location='home.php';">Cancel</button>
                  </div>
                  </div>
				  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
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

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
  </body>
</html>