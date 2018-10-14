<?php
include 'init.php';

if(empty($_POST)===false){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if(user_priviledge($username) > 0){

			$login = priviledged_login($username,$password);
			//echo $login;
		if($login === 0){
		//	echo "Error found";
			header('Location: ../../../access/access_error.php');
			exit();
			
		}else{
			//Set user unique session
			$_SESSION['ID']= $login;
			//Redirect User to home
			header('Location: ../../../home/home.php?user_id='.$login);
			exit();
			}
		}else{
			//$errors[]  = 'Username does not exist. Have you registered ?';
			header('Location: ../../../access/register_error.php');
			exit();			
		}
}
?>