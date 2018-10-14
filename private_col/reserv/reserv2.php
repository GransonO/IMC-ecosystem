<?php
include 'init.php';	
$cat = $_SESSION['category'];
$plan = $_GET['plan'];
$title;
$img_1;
$name = $_GET['fn'].' '.$_GET['ln'];

switch($cat){
	
	case "exd":
		$title = "Executive Double";
		$img_1 = "ex2.jpg";
		break;
		
	case "exs":
		$title = "Executive Single";
		$img_1 = "ex1.jpg";
		break;
		
	case "dxd":
		$title = "Deluxe Double";
		$img_1 = "dx2.jpg";
		break;
		
	case "dxs":
		$title = "Deluxe Single";
		$img_1 = "dx1.jpg";
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

            <div class="page-header" style="background-image:url(assets/images/header-1.jpg)">
				<div class=" animated fadeIn" style="text-align:center;">
                        <a href="index.html">
                            <img class="logo-desktop" src="assets/images/logo.png" alt="Alternate Text"  height=200 width=400 />
                        </a>
                    </div>
                <div class="container">
                    <h2 class="title">Reservation completed</h2>
                    <p>Thank you!</p>
                </div>

            </div>

            <!-- ===  Step wrapper === -->

            <div class="step-wrapper">
                <div class="container">

                    <div class="stepper">
                        <ul class="row">
                            <li class="col-md-4 active">
                                <a href="#l"><span data-text="Room & rates"></span></a>
                            </li>
                            <li class="col-md-4 active">
                                <a href="#"><span data-text="Reservation"></span></a>
                            </li>
                            <li class="col-md-4 active">
                                <a href="#"><span data-text="Checkout"></span></a>
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

                                        <div class="white-block">

                                            <div class="h4">Guest information</div>

                                            <hr />

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Name</strong> <br />
                                                        <span id="name"><?php echo $name;?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Email</strong><br />
                                                        <span id="mail"><?php echo $_GET['mail'];?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Phone</strong><br />
                                                        <span id="phone"><?php echo $_GET['phone'];?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Zip</strong><br />
                                                        <span><?php echo $_GET['zip'];?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>City</strong><br />
                                                        <span id="city"><?php echo $_GET['city'];?></span>
                                                    </div>
                                                </div>
												
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Country</strong><br />
                                                        <span id="country"><?php echo $_GET['co'];?></span>
                                                    </div>
                                                </div>

                                            </div>

                                        </div> <!--/col-md-6-->

                                    </div>

                                    <!-- === right content === -->

                                    <div class="col-md-6">
                                        <div class="white-block">

                                            <div class="h4">Reservation details</div>

                                            <hr />

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Arrival Date</strong> <br />
                                                        <span id="arrive_date"><?php echo $_SESSION['arrive'];?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Departure Date</strong> <br />
                                                        <span id="depart_date"><?php echo $_SESSION['depart'];?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Identification Number</strong> <br />
                                                        <span id="identification"><?php echo $_GET['id'];?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <strong>Number of persons</strong> <br />
                                                        <span id="persons"><?php echo $_SESSION['persons'];?></span>
                                                    </div>
                                                </div>


                                            </div>
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
                                            <strong>Arrival date</strong>  <a href="#"><?php echo $_SESSION['arrive'];?></a>
                                        </div>
                                        <div>
                                            <strong>Guests</strong>  <?php echo $_SESSION['persons'];?>
                                        </div>
                                        <div>
                                            <strong>Departure date</strong>  <a href="#"><?php echo $_SESSION['depart'];?></a>
                                        </div>
                                    </div>
                                    <div class="price">
                                        <span class="final h3" id="amount"><?php echo $plan - ($plan*0.08);?></span>
                                        <span class="discount">Ksh <?php echo ($plan*0.08);?></span>
                                    </div>
                                    <!--delete-this-item-->
                                    <span class="icon icon-cross icon-delete"></span>
                                </div>

                            </div>
                            <!--cart prices -->
							
                            <div class="clearfix">
                                <div class="cart-block cart-block-footer clearfix">
                                    <div>
                                        <strong>Discount</strong>
                                    </div>
                                    <div>
                                        <span>Ksh <?php echo ($plan*0.08);?></span>
                                    </div>
                                </div>

                            </div>


                            <!-- ========================  Cart navigation ======================== -->

                            <div class="clearfix">
                                <div class="cart-block cart-block-footer cart-block-footer-price clearfix">
                                    <div>
                                        <a href="reserv2.php?cat=<?php echo $cat;?>" class="btn btn-clean-dark">Back</a>
                                    </div>
                                    <div>
                                        <a  id="confirm_transaction" class="btn btn-main">Confirm</span></a>
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