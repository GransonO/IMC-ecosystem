<?php
	//include the init file for all functions
include '../query/php_files/core/init.php';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="../images/apple-icon.png">
	<link rel="icon" type="image/png" href="../images/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Kongoni | Rewards Settings</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
	
	<!--     Fonts and icons     -->
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">

	<!-- CSS Files -->
    <link href="../ini/assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../ini/assets/css/gsdk-bootstrap-wizard.css" rel="stylesheet" />
	<!-- This is what you need -->
	<link rel="stylesheet" href="../query/dist/sweetalert.css">

	<!-- CSS Just for demo purpose, please include it in your project -->
	<link href="../ini/assets/css/demo.css" rel="stylesheet" />

	<style>
	table {
	    font-family: sans-serif;
	    font-size: 7;
	    border-collapse: collapse;
	    width: 100%;	
		background-color: #cccccc;
		border-radius: 5px;
		position: relative;
	  	overflow: auto;
	  	text-align: center;
	}

	td, th {
	    border: 1px solid #dddddd;
	    text-align: left;
	    padding: 8px;
	}

	tr:nth-child(even) {
	    background-color: #dddddd;
	}
	</style>
</head>

<body>
<div class="image-container set-full-height" style="background-image: url('../images/kongoni_2.jpg')">
<div class="image-container set-full-height" style="background: rgba(46,46,46,0.9);">

    <!--   Big container   -->
    <div class="container">
        <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <!--      Wizard container        -->
            <div class="wizard-container">
                <div class="card wizard-card" data-color="red" id="wizard">
                <form action="" method="">
                <!--        You can switch ' data-color="green" '  with one of the next bright colors: "blue", "green", "orange", "red"          -->

                    	<div class="wizard-header">
							<h3>
								<div style="text-align:center;">
									<img src="../images/kongoni_logo.png"  height="80" width="160">
								</div>
                        	</h3>
                    	</div>
								<div class="wizard-navigation">
									<ul>
			                            <li><a href="#address" data-toggle="tab">Rewards Settings</a></li>
			                        </ul>
								</div>

		                        <div class="tab-content">
		                        								
		                            <div class="tab-pane" id="address">
		                                <div class="row">
		                                    <div class="col-sm-12">
		                                        <h4 class="info-text"> Fill in all the provided entries </h4>
		                                    </div>
											
		                                    <div class="col-sm-5 col-sm-offset-1">
	                                        	<div class="form-group label-floating">
	                                        		<label class="control-label">Item Name</label>
	                                    			<input type="text" id="item_name" class="form-control">
	                                        	</div>
		                                    </div>
		                                    <div class="col-sm-5">
		                                        <div class="form-group label-floating">
		                                            <label class="control-label">Points Threshold</label>
		                                            <input type="number" id="threshold_points" class="form-control">
		                                        </div>
		                                    </div>
		                                    <div class="col-sm-10 col-sm-offset-1">
		                                        <div class="form-group label-floating">
		                                            <label class="control-label">Expiry Date</label>
		                                            <input type="date" id="expiry_date" class="form-control">
		                                        </div>
		                                    </div>
											<div style="text-align:center;">
												<input type='button' class='btn btn-finish btn-fill btn-success btn-wd btn-sm' id="new_rewards_entry" name='finish' value='Post Details' />
												<input type='button' class='btn btn-finish btn-fill btn-info btn-wd btn-sm' id='admin_cancel' name='finish' value='Cancel'/>
											</div>
											
		                                    <div class="col-sm-12 ">
		                                    </br>
		                                    <p style = "text-align: center;">All rewards (Currently running & Expired)</p>									   	
												<table>
												<thead>
												  <tr>
												    <th style="text-align:center;">Reward item</th>
												    <th style="text-align:center;">Threshold</th>
												    <th style="text-align:center;">Expiry Date</th>
												    <th style="text-align:center;">Action</th>
												  </tr>
												</thead>
												<tbody>
												<?php
					
													//Queries customer table values
													$Table_query  = "SELECT * FROM rewards_opt_tbl";
													$tablequery = mysqli_query(Connect_Database(),$Table_query);
													while($table_data = mysqli_fetch_assoc($tablequery)){
													
														echo '<tr>';
														echo '<td  style="text-align:center;">' .$table_data['offer'] . '</td>' ; 
														echo '<td  style="text-align:center;">' .$table_data['threshold'] . '</td>' ; 		
														echo '<td  style="text-align:center;">' .$table_data['deactivated_on'] . '</td>' ; 		
														echo '<td  style="text-align:center;">	
															<a href="../query/php_files/delete_details_posting.php?del='.$table_data['id'].'" class="btn btn-finish btn-fill btn-danger btn-sm">delete</a>
														</td>' ; 														
														echo '</tr>';  
													}
												 ?> 
												</tbody>  
												</table>
		                                    </div>
																						
		                                </div>
		                            </div>
		                        </div>
		                       <div class="wizard-footer">
                            	<div class="pull-right">
                                    <input type='button' class='btn btn-next btn-fill btn-success btn-wd btn-sm' name='next' value='Next' />

                                </div>
                                <div class="clearfix"></div>
                        </div>

                    </form>
                </div>
            </div> <!-- wizard container -->
        </div>
        </div> <!-- row -->							
		</div> <!--  big container -->
			<div class="footer">
			   <div class="container">
					 Developed and maintained by 
					 <a href="http://www.indulgencemarketing.co.ke/"> 
						<div class="">
							<img src="../images/logo.png" height="70" width="150">
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</body>

	<!--   Core JS Files   -->
	<script src="../ini/assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
	<script src="../ini/assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../ini/assets/js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="../ini/assets/js/gsdk-bootstrap-wizard.js"></script>

	<!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
	<script src="../ini/assets/js/jquery.validate.min.js"></script>

  <script src="../query/dist/sweetalert-dev.js"></script>
  <script type='text/javascript' src='../query/script_files/func.js'></script>

</html>

