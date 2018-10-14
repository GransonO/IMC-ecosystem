<?PHP
//include the init file for all functions
include '../../query/php_files/core/init.php';
$user_id = $_SESSION['ID'];

	//Get User Details
$User_Data = "SELECT `username` AS Name,`email` AS mail FROM privileged_users WHERE `password` = '$user_id'"; 
$U_query =  mysqli_fetch_assoc(mysqli_query(Connect_Database(),$User_Data));
$username = $U_query['Name'];
//Gets the date time of redemption
	date_default_timezone_set('Africa/Nairobi');
	$insert_date = date('Y-m-d H:i:s');
	$upload_id = date('msHYid');
	$file_date = date('Y-m-d H_s');

	 if (!empty($_FILES)) {
	
		$temp = explode(".", $_FILES["file"]["name"]);
		$original_name = $file_date.($_FILES['file']['name']);
		$newfilename = round(microtime(true)) . '.' . end($temp);
		$state = move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $original_name);
		
		if($state){
		//Post upload to DB
		$new_upload = "INSERT INTO `interpel_uploaded_files` 
		(`file_name`, `upload_date`, `file_id`, `uploaded_by`, `status`) 
		VALUES 
		('$original_name', '$insert_date', '$upload_id', '$username', 'uploaded');";
		mysqli_query(Connect_Database(),$new_upload);
		
		echo $new_upload;			
			
		}		 
	} 
?>   