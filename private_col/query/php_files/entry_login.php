<?php
include 'core/init.php';

//Gets the date time of redemption
	date_default_timezone_set('Africa/Nairobi');
	$insert_date = date('Y-m-d H:i:s');
		
//if(empty($_POST)===false){
	
	$username = $_POST['login_username'];
	$password = $_POST['login_pass'];
	$selected_profile = $_POST['selected_profile'];
		
	
	
		if(user_exists($username) < 1){
			//User Does Not Exist.	
			echo 0;
		
		}else{
			
			$login = login($username,$password);	
			
		if($login === 0){	
		
			//Combination error
			echo 1;
		
		}else{
			
		$_SESSION['ID']= $login;
		
		$profile_query = mysqli_query(Connect_Database(),"SELECT `profile` as profile FROM `privileged_users` WHERE `password` = '$login'");
		$profile_data = mysqli_fetch_assoc($profile_query);
		$user_profile = $profile_data['profile'];
		
		$_SESSION['profile'] = $user_profile; 
		$_SESSION['assignee'] = $username; 
		if($user_profile === $selected_profile){

		//Set user unique session
			$new_user = "INSERT INTO `user_tracker` (`id`, `name`, `date_time`) VALUES (NULL, '$username', '$insert_date');";
			mysqli_query(Connect_Database(),$new_user);
			switch($user_profile){
				case 'MANAGEMENT';
			echo 2;	
				break;
				case 'MARKETING';
			echo 5;	
				break;
				case 'FRONTDESK';
			echo 8;	
				break;
				case 'IT';
			echo 7;	
				break;
			}		
			//Redirect User to home
			}else{
				//Wrong Profile
				echo 3;
			}
		} 
	}	
//}

?>

