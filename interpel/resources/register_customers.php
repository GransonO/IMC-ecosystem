<?php
//include the init file for all functions
include 'init.php'; 

	//Gets the date time of redemption
	date_default_timezone_set('Africa/Nairobi');
	$create_date = date('Y-m-d H:i:s');
	
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Indulgence Admin.Point |</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://colorlib.com/polygon/gentelella/css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
		
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
            <form action="" method="post">
              <h1>Client Portal Registry</h1>			  
                  <p>Fill all entries as required</p>
              <div>
			    <input type="text" class="form-control" name="username"	placeholder="Username" required="required" />
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
	<!-- PNotify -->
    <script src="../vendors/pnotify/dist/pnotify.js"></script>
    <script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <!-- PNotify -->
<?php 

	
	if(empty($_POST['password']) === false){
		if($_POST['password'] === $_POST['con_password'] ){
				
				$username = $_POST['username'];
				$password = $_POST['password'];
				
				$_SESSION['NAME'] = $username;
				$_SESSION['PASS'] = $_POST['password'];
				
				$le_pass = md5($password);
				
				$select_id = "SELECT COUNT(`member_id`) AS ID FROM `member_contact` WHERE `member_id` = '$username'";
				$id_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(), $select_id));
				$id_count = $id_query ['ID'];
				
				echo $id_count;
				
		if( $id_count > 0){
				
			$insert_into_users = "UPDATE `member_contact` SET `password` = '$le_pass' WHERE
			`member_id` = '$username';";
				
			$zeal = mysqli_query(Connect_Database(), $insert_into_users)? 1:0 ;
				
			//	echo $insert_into_users."</br>";
			//	echo $zeal."</br>";
					if($zeal === 1){
							header('Location: register_customer_success.php');
					}else{
							echo '<script>
										$(document).ready(function() {
										new PNotify({
										title: \'Registry Error\',
										text: \'Could not register\n Please contact administrator\',
										type: \'info\',
										styling: \'bootstrap3\'
									   });

							});
							</script>';
					}
				
				}
				else{
						echo '<script>
								$(document).ready(function() {
								new PNotify({
								title: \'Registry Error\',
								text: \'The Member ID does not exist\n Contact the Administrators for assistance\',
								type: \'error\',
								styling: \'bootstrap3\'
							   });

					});
					</script>';
				}
				

			}else{
					echo '<script>
							$(document).ready(function() {
									new PNotify({
									title: \'Password Match Error\',
									text: \'Your password does not match the confirmed password.\',
									type: \'error\',
									styling: \'bootstrap3\'
								   });

							});
							</script>';
			}
	}
	?>
	
		
    <!-- Custom Notification -->
    <script>
      $(document).ready(function() {
        var cnt = 10;

        TabbedNotification = function(options) {
          var message = "<div id='ntf" + cnt + "' class='text alert-" + options.type + "' style='display:none'><h2><i class='fa fa-bell'></i> " + options.title +
            "</h2><div class='close'><a href='javascript:;' class='notification_close'><i class='fa fa-close'></i></a></div><p>" + options.text + "</p></div>";

          if (!document.getElementById('custom_notifications')) {
            alert('doesnt exists');
          } else {
            $('#custom_notifications ul.notifications').append("<li><a id='ntlink" + cnt + "' class='alert-" + options.type + "' href='#ntf" + cnt + "'><i class='fa fa-bell animated shake'></i></a></li>");
            $('#custom_notifications #notif-group').append(message);
            cnt++;
            CustomTabs(options);
          }
        };

        CustomTabs = function(options) {
          $('.tabbed_notifications > div').hide();
          $('.tabbed_notifications > div:first-of-type').show();
          $('#custom_notifications').removeClass('dsp_none');
          $('.notifications a').click(function(e) {
            e.preventDefault();
            var $this = $(this),
              tabbed_notifications = '#' + $this.parents('.notifications').data('tabbed_notifications'),
              others = $this.closest('li').siblings().children('a'),
              target = $this.attr('href');
            others.removeClass('active');
            $this.addClass('active');
            $(tabbed_notifications).children('div').hide();
            $(target).show();
          });
        };

        CustomTabs();

        var tabid = idname = '';

        $(document).on('click', '.notification_close', function(e) {
          idname = $(this).parent().parent().attr("id");
          tabid = idname.substr(-2);
          $('#ntf' + tabid).remove();
          $('#ntlink' + tabid).parent().remove();
          $('.notifications a').first().addClass('active');
          $('#notif-group div').first().css('display', 'block');
        });
      });
    </script>
    <!-- /Custom Notification -->
  </body>
</html>