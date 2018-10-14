<?php
include '../query/php_files/core/init.php';
$USER_ID = $_SESSION['ID'];

//Get Total Registered clients
$total_data = "SELECT SUM(`registered_count`) AS sum_count FROM privileged_users";
$total_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$total_data));
$total_number = $total_query['sum_count'];

//Get User Details
$User_Data = "SELECT `username` AS Name, `phone_no` as number, `email` as email,`registered_count` AS registered_count FROM privileged_users WHERE `password` = '$USER_ID'";
$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
$username = $U_query['Name'];
$number = $U_query['number'];
$email = $U_query['email'];
$registered_count = $U_query['registered_count']*(100/$total_number);

//Get top Five Values
$query = "SELECT *
          FROM `privileged_users`
          ORDER BY `registered_count` DESC LIMIT 5;";
$result = mysqli_query(Connect_Database(),$query);
$arrayofrows = array();
while($row = mysqli_fetch_all($result))
{
   $arrayofrows = $row;
}
//1
$first_name = $arrayofrows[0][1];
$first_count = $arrayofrows[0][5]*(100/$total_number);
//2
$sec_name = $arrayofrows[1][1];
$sec_count = $arrayofrows[1][5]*(100/$total_number);

//3
$third_name = $arrayofrows[2][1];
$third_count = $arrayofrows[2][5]*(100/$total_number);

//4
$forth_name = $arrayofrows[3][1];
$forth_count = $arrayofrows[3][5]*(100/$total_number);

//5
$fifth_name = $arrayofrows[4][1];
$fifth_count = $arrayofrows[4][5]*(100/$total_number);

?>
<!DOCTYPE HTML>
<html>
	<head>
		<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="js/jquery.min.js"></script>
		 <!-- Custom Theme files -->
		<link href="css/style.css" rel='stylesheet' type='text/css' />
   		 <!-- Custom Theme files -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		</script>
	</head>
	<body>
		<div class="main">
		<!----container---->
		<div class="container">
			<!----- content ----->
			<div class="">
				<div class="col-md-3">
					<div class="1-col-grids">
						<!---- PIE CHART ---->
						<div class="pie-chart">
							<div class="pie-chrt-head">
								<h2>PERFORMANCE CHART <br><p><small style="color:#FFF;"><?php echo $username;?>: <?php echo round($registered_count);?>% score.</small></p></h2>								
							</div>
							<!----up-load-stats---->
						<div class="up-load-stats">
						<div class="chart">
							<!-----upload-js-files---->
								<script type="text/javascript" src="js/Chart.js"></script>
							<!---//upload-js-files---->
					                <div class="diagram">
					                  <canvas id="canvas" height="210" width="210"> </canvas>
					                 </div>
									<div class="chart_list text-left">
						           	  <ul class="list-unstyled text-left">
						           	  	<li><span class="color1"> </span><?php echo $first_name;?><label><?php echo round($first_count);?>%</label><div class="clearfix"> </div></li>
						           	  	<li><span class="color2"> </span><?php echo $sec_name;?><label><?php echo round($sec_count);?>%</label><div class="clearfix"> </div></li>
						           	  	<li><span class="color3"> </span><?php echo $third_name;?><label><?php echo round($third_count);?>%</label><div class="clearfix"> </div></li>
						           	  	<li><span class="color4"> </span><?php echo $forth_name;?><label><?php echo round($forth_count);?>%</label><div class="clearfix"> </div></li>
						           	  	<li><span class="color5"> </span><?php echo $fifth_name;?><label><?php echo round($fifth_count);?>%</label><div class="clearfix"> </div></li>
						           	  	<div class="clearfix"> </div>	
						           	  </ul>
						           </div>
								   <?php echo '
								   
								   <script>
								   
									var doughnutData = [
											{
												value: '.round($first_count).',
												color:"#e900ff"
											},
											{
												value : '.round($sec_count).',
												color : "#00ff0c"
											},							
											{
												value : '.round($third_count).',
												color : "#fcd600"
											},	
											{
												value : '.round($forth_count).',
												color : "#ff0000"
											},	
											{
												value : '.round($fifth_count).',
												color : "#0051e0"
											},							
										
										];				
										var myDoughnut = new Chart(document.getElementById("canvas").getContext("2d")).Doughnut(doughnutData);					
								</script>
								   
								   '?>
						           
					          </div>
						</div>
						<!--//up-load-stats---->
						</div>
						<!---- PIE CHART ---->
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
			</div>
			<!----- content ----->
		</div>
		<!----container---->
		</div>
	</body>
</html>

