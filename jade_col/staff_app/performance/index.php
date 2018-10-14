<?php
include '../query/php_files/core/init.php';
$USER_ID = $_SESSION['ID'];
	if($USER_ID === null){
		//Show loggin error
	header('Location: ../access/access_error.php');
	
	}else{
		//do nothing
	}
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
$registered_count = $U_query['registered_count'];

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
$first_count = $arrayofrows[0][5];
//2
$sec_name = $arrayofrows[1][1];
$sec_count = $arrayofrows[1][5];

//3
$third_name = $arrayofrows[2][1];
$third_count = $arrayofrows[2][5];

//4
$forth_name = $arrayofrows[3][1];
$forth_count = $arrayofrows[3][5];

//5
$fifth_name = $arrayofrows[4][1];
$fifth_count = $arrayofrows[4][5];

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Performance</title>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<style>
@import url(http://fonts.googleapis.com/css?family=Roboto:500,100,300,700,400);

* {
  margin: 0;
  padding: 0;
  font-family: roboto;
}

body { 
		background-image:url(../images/wall2.jpg);
		background-repeat:no-repeat;
		background-attachment:fixed;
		background-position:center;
		background-size:cover;
		-webkit-background-size:cover;
		-moz-background-size:cover; }
.cont {
  width: 93%;
  max-width: 350px;
  text-align: center;
  margin: 4% auto;
  padding: 30px 0;
  background: #025e02;
  color: #EEE;
  border-radius: 5px;
  border: thin solid #444;
  overflow: hidden;
}

hr {
  margin: 20px;
  border: none;
  border-bottom: thin solid rgba(255,255,255,.1);
}

div.title { font-size: 1em; }

h2 span {
  font-weight: 10;
  color: #Fd4;
}

div.stars {
  width: 270px;
  display: inline-block;
}

input.star { display: none; }

label.star {
  float: right;
  padding: 10px;
  font-size: 36px;
  color: #444;
  transition: all .2s;
}

input.star:checked ~ label.star:before {
  content: '\f005';
  color: #FD4;
  transition: all .25s;
}

input.star-5:checked ~ label.star:before {
  color: #FE7;
  text-shadow: 0 0 20px #952;
}
input.star2-5:checked ~ label.star:before {
  color: #FE7;
  text-shadow: 0 0 20px #952;
}
input.star3-5:checked ~ label.star:before {
  color: #FE7;
  text-shadow: 0 0 20px #952;
}
input.star4-5:checked ~ label.star:before {
  color: #FE7;
  text-shadow: 0 0 20px #952;
}
input.star5-5:checked ~ label.star:before {
  color: #FE7;
  text-shadow: 0 0 20px #952;
}

input.star-1:checked ~ label.star:before { color: #F62; }

label.star:hover { transform: rotate(-15deg) scale(1.3); }

label.star:before {
  content: '\f006';
  font-family: FontAwesome;
}
</style>
<link href="http://www.cssscript.com/wp-includes/css/sticky.css" rel="stylesheet" type="text/css">
</head>
<body>

<div style="overflow:show;">
<div class="cont" style="margin-top:100px;">
  <div class="title">
	<h2> <?php echo $first_name;?> Performance <br><p><small style="color:#FFF;"> <?php echo $first_count;?> score.</small></p></h2>								
							
  </div>
  <div class="stars">
    <form action="">
      <input class="star star-5" id="star-5" type="radio" name="star"/>
      <label class="star star-5" for="star-5"></label>
      <input class="star star-4" id="star-4" type="radio" name="star"/>
      <label class="star star-4" for="star-4"></label>
      <input class="star star-3" id="star-3" type="radio" name="star"/>
      <label class="star star-3" for="star-3"></label>
      <input class="star star-2" id="star-2" type="radio" name="star"/>
      <label class="star star-2" for="star-2"></label>
      <input class="star star-1" id="star-1" type="radio" name="star"/>
      <label class="star star-1" for="star-1"></label>
    </form>
  </div>
  <p>Total Clients <?php echo $first_count;?></p>
  
</div>

<div class="cont" style="margin-top:50px;">
  <div class="title">
	<h2><?php echo $sec_name;?> Performance <br><p><small style="color:#FFF;"> <?php echo $sec_count;?> score.</small></p></h2>								
							
  </div>
  <div class="stars">
    <form action="">
      <input class="star star-5" id="star2-5" type="radio" name="star2"/>
      <label class="star star-5" for="star2-5"></label>
      <input class="star star-4" id="star2-4" type="radio" name="star2"/>
      <label class="star star-4" for="star2-4"></label>
      <input class="star star-3" id="star2-3" type="radio" name="star2"/>
      <label class="star star-3" for="star2-3"></label>
      <input class="star star-2" id="star2-2" type="radio" name="star2"/>
      <label class="star star-2" for="star2-2"></label>
      <input class="star star-1" id="star2-1" type="radio" name="star2"/>
      <label class="star star-1" for="star2-1"></label>
    </form>
  </div>
  <p>Total Clients <?php echo $sec_count;?></p>
  
</div>

<div class="cont" style="margin-top:50px;">
  <div class="title">
	<h2><?php echo $third_name;?> Performance <br><p><small style="color:#FFF;"> <?php echo $third_count;?> score.</small></p></h2>								
							
  </div>
  <div class="stars">
    <form action="">
      <input class="star star-5" id="star3-5" type="radio" name="star3"/>
      <label class="star star-5" for="star3-5"></label>
      <input class="star star-4" id="star3-4" type="radio" name="star3"/>
      <label class="star star-4" for="star3-4"></label>
      <input class="star star-3" id="star3-3" type="radio" name="star3"/>
      <label class="star star-3" for="star3-3"></label>
      <input class="star star-2" id="star3-2" type="radio" name="star3"/>
      <label class="star star-2" for="star3-2"></label>
      <input class="star star-1" id="star3-1" type="radio" name="star3"/>
      <label class="star star-1" for="star3-1"></label>
    </form>
  </div>
  <p>Total Clients <?php echo $third_count;?></p>
  
</div>

<div class="cont" style="margin-top:50px;">
  <div class="title">
	<h2> <?php echo $forth_name;?> Performance <br><p><small style="color:#FFF;"><?php echo $forth_count;?> score.</small></p></h2>								
							
  </div>
  <div class="stars">
    <form action="">
      <input class="star star-5" id="star4-5" type="radio" name="star4"/>
      <label class="star star-5" for="star4-5"></label>
      <input class="star star-4" id="star4-4" type="radio" name="star4"/>
      <label class="star star-4" for="star4-4"></label>
      <input class="star star-3" id="star4-3" type="radio" name="star4"/>
      <label class="star star-3" for="star4-3"></label>
      <input class="star star-2" id="star4-2" type="radio" name="star4"/>
      <label class="star star-2" for="star4-2"></label>
      <input class="star star-1" id="star4-1" type="radio" name="star4"/>
      <label class="star star-1" for="star4-1"></label>
    </form>
  </div>
  <p>Total Clients <?php echo $forth_count;?></p>
  
</div>

<div class="cont" style="margin-top:50px;">
  <div class="title">
	<h2><?php echo $fifth_name;?> Performance <br><p><small style="color:#FFF;"><?php echo $fifth_count;?> score.</small></p></h2>								
							
  </div>
  <div class="stars">
    <form action="">
      <input class="star star-5" id="star5-5" type="radio" name="star5"/>
      <label class="star star-5" for="star5-5"></label>
      <input class="star star-4" id="star5-4" type="radio" name="star5"/>
      <label class="star star-4" for="star5-4"></label>
      <input class="star star-3" id="star5-3" type="radio" name="star5"/>
      <label class="star star-3" for="star5-3"></label>
      <input class="star star-2" id="star5-2" type="radio" name="star5"/>
      <label class="star star-2" for="star5-2"></label>
      <input class="star star-1" id="star5-1" type="radio" name="star5"/>
      <label class="star star-1" for="star5-1"></label>
    </form>
  </div>
  <p>Total Clients <?php echo $fifth_count;?></p>
  
</div>

</div>
<script>
function check() {
    document.getElementById("star-5").checked = true;
    document.getElementById("star2-3").checked = true;
    document.getElementById("star3-4").checked = true;
    document.getElementById("star4-1").checked = true;
    document.getElementById("star5-2").checked = true;
}
check();
</script>
</body>
</html>