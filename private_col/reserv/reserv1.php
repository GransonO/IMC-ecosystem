<?php
include 'init.php';

$cat = $_SESSION['category'];
$arr_date = $_GET['arr'];
$dep_date = $_GET['dep'];
$person = $_GET['oc'];
$_SESSION['arrive'] = $arr_date;
$_SESSION['depart'] = $dep_date;
$_SESSION['persons'] = $person;
$title;
$bb_amount;
$hb_amount;
$fb_amount;
$discount;
$img_1;

switch($cat){
	
	case "exd":
		$title = "Executive Double";
		$img_1 = "ex2.jpg";
		$bb_amount = 15000;
		$hb_amount = 18000;
		$fb_amount = 21000;
		break;
		
	case "dxd":
		$title = "Deluxe Double";
		$img_1 = "dx2.jpg";
		$bb_amount = 12000;
		$hb_amount = 15000;
		$fb_amount = 18000;
		break;	
		
	case "exs":
		$title = "Executive Single";
		$img_1 = "dx1.jpg";
		$bb_amount = 12500;
		$hb_amount = 14000;
		$fb_amount = 15500;
		break;
		
	case "dxs":
		$title = "Deluxe Single";
		$img_1 = "dx1.jpg";
		$bb_amount = 9500;
		$hb_amount = 11000;
		$fb_amount = 13176;
		break;

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Mobile Web-app fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Meta tags -->
    <meta name="description" content="Sunside Guest House, Resort & Accommodation,Nairobi Kenya,Ruai Town Kenya, Makongeni Kenya, Ruai Churches Kenya,">
    <meta name="author" content="Africa Apeiron">
    <link rel="icon" href="favicon.ico">

    <!--Title-->
    <title>Sunside Guest House, Resort & Accommodation</title>

    <!--CSS styles-->
    <link rel="stylesheet" media="all" href="css/bootstrap.css" />
    <link rel="stylesheet" media="all" href="css/animate.css" />
    <link rel="stylesheet" media="all" href="css/font-awesome.css" />
    <link rel="stylesheet" media="all" href="css/linear-icons.css" />
    <link rel="stylesheet" media="all" href="css/hotel-icons.css" />
    <link rel="stylesheet" media="all" href="css/magnific-popup.css" />
    <link rel="stylesheet" media="all" href="css/owl.carousel.css" />
    <link rel="stylesheet" media="all" href="css/datepicker.css" />
    <link rel="stylesheet" media="all" href="css/theme.css" />

    <!--Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,500&amp;subset=latin-ext" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&amp;subset=latin-ext" rel="stylesheet">

</head>

<body>

    <div class="page-loader"></div>

    <div class="wrapper">

       
        <!-- ========================  Checkout ======================== -->

        <section class="page">

            <!-- ===  Page header === -->

            <div class="page-header" style="background-image:url(assets/images/food-1.jpg)">
                <div class="container">
				<div class=" animated fadeIn" style="text-align:center;">
                        <a href="index.html">
                            <img class="logo-desktop" src="assets/images/logo.png" alt="Alternate Text"  height=200 width=400 />
                        </a>
                    </div>
                    <h2 class="title">Confirm your reservation</h2>
                    <p>Guest information</p>
                </div>
            </div>

            <!-- ===  Step wrapper === -->

            <div class="step-wrapper">
                <div class="container">
                    <div class="stepper">
                        <ul class="row">
                            <li class="col-md-4 active">
                                <a href="reservation-1.html"><span data-text="Room & rates"></span></a>
                            </li>
                            <li class="col-md-4 active">
                                <a href="reservation-2.html"><span data-text="Reservation"></span></a>
                            </li>
                            <li class="col-md-4">
                                <a href="reservation-3.html"><span data-text="Checkout"></span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ===  Checkout === -->

            <div class="checkout">

                <div class="container">

                    <div class="clearfix">

                        <!-- ========================  Note block ======================== -->

                        <div class="cart-wrapper">

                            <div class="note-block">

                                <div class="row">

                                    <!-- === left content === -->

                                    <div class="col-md-6">

                                        <!-- === login-wrapper === -->

                                        <div class="login-wrapper">

                                            <div class="white-block">

											<!--signup-->

                                                <div class="login-block login-block-signup">

                                                    <div class="h4">Enter your details</div>

                                                    <hr />

                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" value="" class="form-control" id="firstname" placeholder="First name: *">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" value=""  class="form-control" id="lastname" placeholder="Last name: *">
                                                            </div>
                                                        </div>

                                                       
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" value="" class="form-control" id="identification" placeholder="ID/Passport Number: *">
                                                            </div>
                                                        </div>
                                                       
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" value="" class="form-control" id="zip" placeholder="Zip code: *">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" value="" class="form-control" id="city" placeholder="City: *">
                                                            </div>
                                                        </div>
														
														<div class="col-md-6">

                                                            <div class="form-group">
                                                                <input type="text" value="" class="form-control" id="country" placeholder="Country:">
                                                            </div>
                                                        </div>
														
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="email" value="" class="form-control" id="mail" placeholder="Email: *">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="phone" value="" class="form-control" id="phone" placeholder="Phone: *">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div> <!--/signup-->
                                            </div>
                                        </div> <!--/login-wrapper-->
                                    </div> <!--/col-md-6-->
                                    <!-- === right content === -->

                                    <div class="col-md-6">

                                        <div class="white-block">

                                            <div class="h4">Choose reservation plan</div>
									
											<div class="col-md-12">
												<hr />
														<span class="checkbox">
															<input type="checkbox" id="checkBoxId1" value ="<?php echo $bb_amount;?>">
															<label for="checkBoxId1">
																<strong>Bed & Breakfast</strong> <br />
																<small><strong>Ksh <?php echo $bb_amount - ($bb_amount*0.08);?></strong> ( Discounted price <strike><strong><?php echo ($bb_amount*0.08);?></strong></strike>)</small>
															</label>
														</span>
														<span class="checkbox">
															<input type="checkbox" id="checkBoxId2" value ="<?php echo $hb_amount;?>">
															<label for="checkBoxId2">
																<strong>Half Board (Breakfast & Dinner)</strong> <br />
																<small><strong>Ksh <?php echo $hb_amount - ($hb_amount*0.08);?></strong> ( Discounted price <strike><strong><?php echo ($hb_amount*0.08);?></strong></strike>)</small>
															</label>
														</span>
														<span class="checkbox">
															<input type="checkbox" id="checkBoxId3" value ="<?php echo $fb_amount;?>">
															<label for="checkBoxId3">
																<strong>full Board (Breakfast,Lunch & Dinner)</strong> <br />
																<small><strong>Ksh <?php echo $fb_amount - ($fb_amount*0.08);?></strong> ( Discounted price <strike><strong><?php echo ($fb_amount*0.08);?></strong></strike>)</small>
															</label>
														</span>
												<hr />
											</div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- ========================  Cart wrapper ======================== -->

                        <div class="cart-wrapper">

                            <!--cart header -->

                            <div class="cart-block cart-block-header clearfix">
                                <div>
                                    <span>Room type</span>
                                </div>
                                <div class="text-right">
                                    <span>Price</span>
                                </div>
                            </div>

                            <!--cart items-->

                            <div class="clearfix">

                                <div class="cart-block cart-block-item clearfix">
                                    <div class="image">
                                        <a href="room-overview.html"><img src="assets/images/rooms/<?php echo $img_1;?>" alt="" /></a>
                                    </div>
                                    <div class="title">
                                        <div class="h2"><a href="room-overview.html"><?php echo $title;?></a></div>
                                        <div>
                                            <strong>Arrival date</strong>  <a href="#"><?php echo $arr_date;?></a>
                                        </div>
                                        <div>
                                            <strong>Guests</strong>  <?php echo $person;?>
                                        </div>
                                        <div>
                                            <strong>Departure date</strong>  <a href="#"><?php echo $dep_date;?></a>
                                        </div>
                                    </div>
                                    <!--delete-this-item-->
                                    <span class="icon icon-cross icon-delete"></span>
                                </div>

                            </div>

                            <!--cart final price -->

                            <!-- ========================  Cart navigation ======================== -->

                            <div class="clearfix">
                                <div class="cart-block cart-block-footer cart-block-footer-price clearfix">
                                    <div>
                                        <a href="room-overview.php?cat=<?php echo $cat;?>" class="btn btn-clean-dark">Back</a>
                                    </div>
                                    <div>
                                        <a  class="btn btn-main" id="check_me_out">Checkout <span class="icon icon-chevron-right"></span></a>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div> <!--/container-->
            </div> <!--/checkout-->

        </section>
    </div> <!--/wrapper-->
    <!--JS files-->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.bootstrap.js"></script>
    <script src="js/jquery.magnific-popup.js"></script>
    <script src="js/jquery.owl.carousel.js"></script>
    <script src="js/main.js"></script>
    <script src="js/func.js"></script>
</body>

</html>