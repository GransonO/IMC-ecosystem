<?php
$jsondata = " Not Parsed yet";
if(empty($_POST['submitted']) === false){
		
$email = $_POST['email'];
$password = $_POST['password'];

$url = 'http://maps.googleapis.com/maps/api/geocode/json?';
$data = array('address' => 'disneyland,ca');

// use key 'http' even if you send the request to https://...

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE)
	{ /* Handle error */
		echo 'There was an error in this process';
	}else
		{
		$jsonData = var_dump($result);
		}

}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
  </head>
<body>
<!--Registration Design-->
<form action="" method="post" class="form-horizontal form-label-left">

  <div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Email <span class="required">*</span>
	</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
	  <input type="email" id="last-name" name="email" required="required" class="form-control col-md-7 col-xs-12">
	</div>
  </div>
  
  <div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Password<span class="required">*</span>
	</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
	  <input type="text" name="password" required="required" class="form-control col-md-7 col-xs-12">
	</div>
  </div>  
  
  <div class="form-group">
	<div>
	  <input type="submit" name="submitted" class="btn btn-success"/>
	 </div>
  </div>
</form>      
<h1>This is the returned Data</h1>
<p>
	<?php echo $jsondata; ?>
</p>
</body>
</html>