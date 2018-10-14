<?php

function recover($user_Id,$email,$Name){
	$email = sanitize($email);
	$Name = sanitize($Name);
	//$email = sanitize($email);
		//Generates a random number and removes the first 8 characters
		$generated_password = substr(md5(rand(999,999999)),0,8);
		change_password($user_Id,$generated_password);
		
		//sends password via email-----Create this email function------------ ************* NB SEND THE FULL LINK IN THE HREF *****************
		send_mail($email,'Recovered Password',"<h2>Hello .'$Name' . </h2></br>Your new password is : <h2><Strong>'$generated_password' </Strong></h2>.</br> <h2>* Please complete the process by reseting the password *</h2></br><Strong><h2><a  href= 'recovery_index.php?mail=$email&&pass=$generated_password&&user=$Name'> Click here to reset the password </a></h2></Strong></br>");
    
}

function change_password($user_id,$password){
	$user_id =$user_id;
	$password = md5($password);
	mysqli_query(Connect_Database(),"UPDATE `privileged_users` SET `password` = '$password' WHERE `ID` = $user_id");
}

//access data collected from the table
function user_data($user_id){
	$data = array();
	$user_id = (int)$user_id;
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if($func_get_args >1){
		unset($func_get_args[0]);
		//sql structuring
		$fields = '`'. implode('`,`', $func_get_args) .'`';
		$query = mysqli_query(Connect_Database(),"SELECT $fields FROM `privileged_users` WHERE `ID` = $user_id");
		$user_data = mysqli_fetch_assoc($query);

		//-------------echo $user_data['username'];---------------------------
		return $user_data;
	}
}

function register_user($register_data){ 
	array_walk($register_data,'array_sanitize');
	$register_data['password'] = md5($register_data['password']);
	
	$fields = '`' . implode('`,`',array_keys($register_data)) . '`';
	$data = '\'' . implode('\',\'', $register_data).'\'';
	mysqli_query(Connect_Database(),"INSERT INTO `privileged_users` ($fields) VALUES ($data)");
	//mysql_query("INSERT INTO `users` ($fields) VALUES ($data)");
}

//Checks if the user is logged in 
function logged_in(){
	return (isset($_SESSION['ID'])) ? true : false;
}

function user_exists($username){
	$username = sanitize($username);
	$query = mysqli_query(Connect_Database(),"SELECT COUNT(`ID`) AS count FROM `privileged_users` WHERE `username`= '$username'");
	$result = (mysqli_fetch_assoc($query));
	$food = $result["count"];
	
	return $food;
}

function email_exists($email){
	$email = sanitize($email);
	$query = mysqli_query(Connect_Database(),"SELECT COUNT(`email`) FROM `privileged_users` WHERE `email`= '$email'");
	return (mysqli_fetch_assoc($query)) ?true : false;
}

function user_active($username){
	$username = sanitize($username);
	$query = mysqli_query(Connect_Database(),"SELECT COUNT(`ID`) FROM `privileged_users` WHERE `username`= '$username'");
	
	return (mysqli_fetch_assoc($query));
}

function user_id_from_username($username){
	$username = sanitize($username);
	$query = mysqli_query(Connect_Database(),"SELECT `ID` FROM `privileged_users` WHERE `username`= '$username'");
	return mysqli_fetch_assoc($query);
}

function user_id_from_email($email){
	$email = sanitize($email);
	$query = mysqli_query(Connect_Database(),"SELECT `ID` FROM `privileged_users` WHERE `email`= '$email'");
	return mysqli_fetch_assoc($query);
}

function login($username,$password){
	//$user_id = user_id_from_username($username);
	
	$username = sanitize($username);
	$password = md5($password);
	$query = mysqli_query(Connect_Database(),"SELECT `password` as count FROM `privileged_users` WHERE `username` = '$username' AND `password` = '$password'");
	$result = (mysqli_fetch_assoc($query));
	$user_id = ($result["count"])? $result["count"]: 0;
	
	return $user_id;
}

function email_verify($username,$email){
	//$user_id = user_id_from_username($username);
	
	$username = sanitize($username);
	$email = sanitize($email);
	$query = mysqli_query(Connect_Database(),"SELECT `id` as count FROM `privileged_users` WHERE `username` = '$username' AND `email` = '$email'");
	$result = (mysqli_fetch_assoc($query));
	$present = ($result["count"])? $result["count"]: 0;
	
	return $present;
}
?>