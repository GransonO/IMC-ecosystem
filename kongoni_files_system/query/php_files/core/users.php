<?php

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
	$query = mysqli_query(Connect_Database(),"SELECT COUNT(`member_id`) AS count FROM `member_contact` WHERE `member_id`= '$username'");
	$result = (mysqli_fetch_assoc($query));
	$food = $result["count"];
	
	return $food;
}

function user_priviledge($login_email){
	$login_email = sanitize($login_email);
	$query = mysqli_query(Connect_Database(),"SELECT COUNT(`email_address`) AS count FROM `users_profile` WHERE `email_address`= '$login_email'");
	$result = (mysqli_fetch_assoc($query));
	$food = $result["count"];
	
	return $food;
}

function email_exists($email){
	$email = sanitize($email);
	$query = mysqli_query(Connect_Database(),"SELECT COUNT(`email_address`) as count FROM `member_contact` WHERE `email_address`= '$email'");
	$result = (mysqli_fetch_assoc($query));
	$count = ($result["count"])? $result["count"]: 0;
	return ($count);
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

function priviledged_login($login_email,$password){
	
	$login_email = sanitize($login_email);
	$password = md5($password);
	$query = mysqli_query(Connect_Database(),"SELECT `teacher_id` as teacher_id FROM `users_profile` WHERE `email_address` = '$login_email' AND `user_pass_id` = '$password'");
	$result = (mysqli_fetch_assoc($query));
	$user_id = ($result["teacher_id"])? $result["teacher_id"]: 0;
	
	return $user_id;
}

function login($username,$password){
	
	$username = sanitize($username);
	$password = md5($password);
	$query = mysqli_query(Connect_Database(),"SELECT `member_id` as count FROM `member_contact` WHERE `member_id` = '$username' AND `password` = '$password'");
	$result = (mysqli_fetch_assoc($query));
	$user_id = ($result["count"])? $result["count"]: 0;
	
	return $user_id;
}
?>