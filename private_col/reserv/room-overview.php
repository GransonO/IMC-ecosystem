<?php
include 'init.php';

$cat = $_GET['cat'];
$_SESSION['category'] = $cat;
$title;
$price;
$img_1;
$img_2;
$img_3;

switch($cat){
	
	case "exd":
		$title = "Executive Double";
		$img_1 = "ex2.jpg";
		$img_2 = "ex1.jpg";
		$img_3 = "ex3.jpg";
		break;
		
	case "exs":
		$title = "Executive Single";
		$img_1 = "ex2.jpg";
		$img_2 = "ex1.jpg";
		$img_3 = "ex3.jpg";
		break;
		
	case "dxd":
		$title = "Deluxe Double";
		$img_1 = "dx2.jpg";
		$img_2 = "dx1.jpg";
		$img_3 = "dx3.jpg";
		break;
		
	case "dxs":
		$title = "Deluxe Single";
		$img_1 = "dx2.jpg";
		$img_2 = "dx1.jpg";
		$img_3 = "dx3.jpg";
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
    <title>Naivasha Kongoni Lodge</title>

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

<body >

    <div class="page-loader"></div>

    <div class="wrapper">


        <!-- ========================  Room ======================== -->

        <section class="page">

            <!-- ===  Page header === -->


            <div class="room">
					<div class=" animated fadeIn" style="text-align:center;">
                        <a href="index.html">
                            <img class="logo-desktop" src="assets/images/logo.png" alt="Alternate Text"  height=220 width=440 />
                        </a>
                    </div>
                <!-- === Room gallery === -->

                <div class="room-gallery">
                    <div class="container">
                        <div class="owl-slider owl-theme owl-slider-gallery">

                            <!-- === slide item === -->

                            <div class="item" style="background-image:url(assets/images/rooms/<?php echo $img_1;?>)">
                                <img src="assets/images/rooms/<?php echo $img_1;?>" alt="" />
                            </div>

                            <!-- === slide item === -->

                            <div class="item" style="background-image:url(assets/images/rooms/<?php echo $img_2;?>)">
                                <img src="assets/images/rooms/<?php echo $img_2;?>" alt="" />
                            </div>


                            <!-- === slide item === -->

                            <div class="item" style="background-image:url(assets/images/rooms/<?php echo $img_3;?>)">
                                <img src="assets/images/rooms/<?php echo $img_3;?>" alt="" />
                            </div>


                        </div> <!--/owl-slider-->

                    </div>
                </div> <!--/room-gallery-->
                <!-- === Booking === -->

                <div class="booking booking-inner">

                    <div class="container">

                        <div class="booking-wrapper">
                            <div class="row">

                                <!--=== date arrival ===-->

                                <div class="col-xs-4 col-sm-3">
                                    <div class="date" id="dateArrival" data-text="Arrival">
                                        <input class="datepicker" readonly="readonly" />
                                        <div class="date-value"></div>
                                    </div>
                                </div>

                                <!--=== date departure ===-->

                                <div class="col-xs-4 col-sm-3">
                                    <div class="date" id="dateDeparture" data-text="Departure">
                                        <input class="datepicker" readonly="readonly" />
                                        <div class="date-value"></div>
                                    </div>
                                </div>

                                <!--=== guests ===-->

                                <div class="col-xs-4 col-sm-2">

                                    <div class="guests" data-text="Guests">
                                        <div class="result">
                                            <input class="qty-result" type="text" value="2" id="qty-result" readonly="readonly" />
                                            <div class="qty-result-text date-value" id="qty-result-text"></div>
                                        </div>
                                        <!--=== guests list ===-->
                                        <ul class="guest-list">

                                            <li class="header">
                                                Please choose number of guests <span class="qty-apply"><i class="icon icon-cross"></i></span>
                                            </li>

                                            <!--=== adults ===-->

                                            <li class="clearfix">

                                                <!--=== Adults value ===-->

                                                <div>
                                                    <input class="qty-amount" value="2" type="text" id="ticket-adult" data-value="2" readonly="readonly" />
                                                </div>

                                                <div>
                                                    <span>Adults <small>16+ years</small></span>
                                                </div>

                                                <!--=== Add/remove quantity buttons ===-->

                                                <div>
                                                    <input class="qty-btn qty-minus" value="-" type="button" data-field="ticket-adult" />
                                                    <input class="qty-btn qty-plus" value="+" type="button" data-field="ticket-adult" />
                                                </div>

                                            </li>

                                            <!--=== chilrens ===-->

                                            <li class="clearfix">

                                                <!--=== Adults value ===-->

                                                <div>
                                                    <input class="qty-amount" value="0" type="text" id="ticket-children" data-value="0" readonly="readonly" />
                                                </div>

                                                <!--=== Label ===-->

                                                <div>
                                                    <span>Children <small>2-11 years</small></span>
                                                </div>


                                                <!--=== Add/remove quantity buttons ===-->

                                                <div>
                                                    <input class="qty-btn qty-minus" value="-" type="button" data-field="ticket-children" />
                                                    <input class="qty-btn qty-plus" value="+" type="button" data-field="ticket-children" />
                                                </div>

                                            </li>

                                            <!--=== Infants ===-->

                                            <li class="clearfix">

                                                <!--=== Adults value ===-->

                                                <div>
                                                    <input class="qty-amount" value="0" type="text" id="ticket-infants" data-value="0" readonly="readonly" />
                                                </div>

                                                <!--=== Label ===-->

                                                <div>
                                                    <span>Infants <small>Under 2 years</small></span>
                                                </div>

                                                <!--=== Add/remove quantity buttons ===-->

                                                <div>
                                                    <input class="qty-btn qty-minus" value="-" type="button" data-field="ticket-infants" />
                                                    <input class="qty-btn qty-plus" value="+" type="button" data-field="ticket-infants" />
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!--=== button ===-->

                                <div class="col-xs-12 col-sm-4">
                                    <a id="reserve_for_me" class="btn btn-clean">
                                        Book now
                                        <small>Best Prices Guaranteed</small>
                                    </a>
                                </div>

                            </div> <!--/row-->
                        </div><!--/booking-wrapper-->
                    </div> <!--/container-->
                </div> <!--/booking-->
                <!-- ===  Room overview === -->

                <div class="room-overview">

                    <div class="container">
                        <div class="row">

                            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">

                                <!-- === Room aminities === -->

                                <div class="room-block room-aminities">

                                    <h2 class="title">Room amenities</h2>

                                    <div class="row">

                                        <!--=== item ===-->

                                        <div class="col-xs-6 col-sm-2">
                                            <figure>
                                                <figcaption>
                                                    <span class="hotelicon hotelicon-air-condition"></span>
                                                    <p>Aircondition</p>
                                                </figcaption>
                                            </figure>
                                        </div>

                                        <!--=== item ===-->

                                        <div class="col-xs-6 col-sm-2">
                                            <figure>
                                                <figcaption>
                                                    <span class="hotelicon hotelicon-king-bed"></span>
                                                    <p>1 Kingsize bed</p>
                                                </figcaption>
                                            </figure>
                                        </div>

                                        <!--=== item ===-->

                                        <div class="col-xs-6 col-sm-2">
                                            <figure>
                                                <figcaption>
                                                    <span class="hotelicon hotelicon-guest"></span>
                                                    <p>2 People</p>
                                                </figcaption>
                                            </figure>
                                        </div>

                                        <!--=== item ===-->

                                        <div class="col-xs-6 col-sm-2">
                                            <figure>
                                                <figcaption>
                                                    <span class="hotelicon hotelicon-wifi"></span>
                                                    <p>Internet</p>
                                                </figcaption>
                                            </figure>
                                        </div>

                                        <!--=== item ===-->

                                        <div class="col-xs-6 col-sm-2">
                                            <figure>
                                                <figcaption>
                                                    <span class="hotelicon hotelicon-phone"></span>
                                                    <p>Telephone</p>
                                                </figcaption>
                                            </figure>
                                        </div>

                                        <!--=== item ===-->

                                        <div class="col-xs-6 col-sm-2">
                                            <figure>
                                                <figcaption>
                                                    <span class="hotelicon hotelicon-roomservice"></span>
                                                    <p>Room service</p>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>

                                </div>

                                <!-- === Room block === -->

                                <div class="room-block">

								<div class="box">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Check-In</strong></p>
                                            </div>
                                            <div class="col-md-8">
                                                <p>10:00 - 22:00</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- === box === -->

                                    <div class="box">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Check-out</strong></p>
                                            </div>
                                            <div class="col-md-8">
                                                <p>07:00 - 10:00</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- === box === -->

                                    <div class="box">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Cancellation/prepayment</strong></p>
                                            </div>
                                            <div class="col-md-8">
                                                <p>
                                                    Cancellation and prepayment policies vary according to room type. Please enter the dates of your stay and check the conditions of your required room.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- === box === -->

                                    <div class="box">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Children and extra beds</strong></p>
                                            </div>
                                            <div class="col-md-8">
                                                <p>All children are welcome.</p>
                                                <p>One child under 6 years is charged EUR 50 per night when using existing beds.</p>
                                                <p>There is no capacity for extra beds in the room.</p>
                                                <p>Supplements are not calculated automatically in the total costs and will have to be paid for separately during your stay.                                        </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- === box === -->

                                    <div class="box">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Pets</strong></p>
                                            </div>
                                            <div class="col-md-8">
                                                <p>Pets are not allowed.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- === box === -->
                                </div>

                            </div> <!--/col-sm-10-->
                        </div> <!--/row-->
                    </div> <!--/container-->
                </div> <!--/room-overview-->
            </div>

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