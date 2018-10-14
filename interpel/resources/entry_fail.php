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
	$confirm_id = $_SESSION['confirm_id'];
	
$Company_Data = "SELECT `company_name` AS name FROM member WHERE `member_id` = '$confirm_id'"; 
$C_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$Company_Data));
$Company = $C_query['name'];

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

  <body class="nav-md" style="background-color:hsla(0,100%,70%,0.7);">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number">ERROR !</h1>
              <h2>New registration for Member id "<?php echo $confirm_id;?>" failed.</h2></br>
			  <h1> Details: </h1>		  
              <h2>The members ID assigned to the client has already been assigned to another member.</h2>
              <h2>Company Name: "<?php echo $Company;?>"</h2>
              <h2>Company ID: "<?php echo $confirm_id;?>"</h2>
			  <h1> Options: </h1>	  
              <h2>1. Assign a new ID to the member to offset the error.</h2></br>
              <h2><a href="http://178.62.100.247/indulgence254point/indulgencePoint3333217/production/support.php?mode=Operational">2. Contact the program administrator for assistance.</a></h2></br>
              </p>
              <div class="mid_center">
                <h3><a href="form.php">BACK</h3>
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