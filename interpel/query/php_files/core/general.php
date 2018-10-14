<?php
function protected_page(){
	if(logged_in() === false)
		header('location: default.php');
	exit;
	
} 
//The and symbol returns the generated value
function array_sanitize(&$item){
	//Loops through each item and returns them to the main array
	$item = htmlentities(strip_tags(mysqli_real_escape_string(Connect_Database(),$item)));
}

function sanitize($data){
	return htmlentities(strip_tags(mysqli_real_escape_string(Connect_Database(),$data)));
}

?>