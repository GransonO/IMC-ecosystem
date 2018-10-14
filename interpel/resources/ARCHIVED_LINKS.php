<?php
//include the init file for all functions
include 'init.php';
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
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">	
	
	<!-- Select2 -->
	<link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">
	<!-- PNotify -->
    <link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
		  
            <!-- Does the login  -->
            <form  method="post">
              <h1>Post Archive Files</h1>
              <div>
			  <input type="text" class="form-control" name="year" placeholder="YEAR" required="required" />	
				<input type="text" class="form-control" name="details" placeholder="DETAILS" required="required" />	
			    <input type="text" class="form-control" name="link" placeholder="FILE LINK" required="required" />
				

					<div style="text-align: center;">

					<select class="select2_single col-md-6 col-sm-6 col-xs-12 form-control"  name="MONTH_SELECTOR">
					<option>select month</option>
					 <option value="JANUARY">JANUARY</option>					
					 <option value="FEBRUARY">FEBRUARY</option>					
					 <option value="MARCH">MARCH</option>					
					 <option value="APRIL">APRIL</option>					
					 <option value="MAY">MAY</option>					
					 <option value="JUNE">JUNE</option>					
					 <option value="JULY">JULY</option>					
					 <option value="AUGUST">AUGUST</option>					
					 <option value="SEPTEMBER">SEPTEMBER</option>					
					 <option value="OCTOBER">OCTOBER</option>					
					 <option value="NOVEMBER">NOVEMBER</option>					
					 <option value="DECEMBER">DECEMBER</option>					
					</select>
					</br>
					</div>
				<div class="clearfix">	
				<p>...</p>				
				<button type="submit" class="btn btn-default submit">submit</button>
				</div>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <div>
                  <h1><i class=""></i> Indulgence Admin.Point!</h1>
                  <p>©2016 All Rights Reserved |. Indulgence Marketing .</br>Terms and Conditions apply</p>
                </div>
              </div>
            </form>
          </section>
        </div>
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
<!-- bootstrap-progressbar -->
<script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<!-- iCheck -->
<script src="../vendors/iCheck/icheck.min.js"></script>
<!-- Select2 -->
<script src="vendors/select2/dist/js/select2.full.min.js"></script>
<!-- Select2 -->
<!-- PNotify -->
<script src="../vendors/pnotify/dist/pnotify.js"></script>
<script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>

 <script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Select your county of interest",
          allowClear: true
        });
        $(".select2_group").select2({});
        $(".select2_multiple").select2({
          maximumSelectionLength: 4,
          placeholder: "With Max Selection limit 4",
          allowClear: true
        });
      });
    </script>
    <!-- /Select2 -->
	
	<?php
			
		if(empty($_POST['link'])===false){

		$link = $_POST['link'];
		$year = $_POST['year'];
		$details = $_POST['details'];
		$MONTH_SELECTOR = $_POST['MONTH_SELECTOR'];

		//Gets the date time of redemption
		date_default_timezone_set('Africa/Nairobi');
		$post_date = date('Y-m-d');
				
		//INSERT THE ACQUIRED DATA INTO THE TABLE
		$INSERT_STATEMENT = "INSERT INTO `archived_files` (`date`, `month`, `details`, `link`, `uploaded_by`)
		 VALUES ('$post_date', '$MONTH_SELECTOR', '$details', '$link', 'Granson');";
		//echo $INSERT_STATEMENT;
		$INSERT_STATEMENT_DONE = mysqli_query(Connect_Database(), $INSERT_STATEMENT);

		if($INSERT_STATEMENT_DONE){
				echo '<script>
				alert("Posting successfull!");
				</script>';
		}else{
				echo '<script>
				alert("Posting not successfull!");
				</script>';
			}
		}
	?>
  </body>  
  
</html>