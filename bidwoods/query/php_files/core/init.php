<?php
session_start();
/**stop error reporting*/

/**includes functions from the mailing functions*/
//require 'C:\xampp\htdocs\member_portal\mailing\vendor/swiftmailer/swiftmailer/lib/swift_required.php';

/**includes functions from the database folder*/
require 'connect.php';

/**includes functions from the functions folder*/
require 'general.php';

/**includes functions from the functions folder*/
require 'users.php'; 

/**Checks and log errors*/
$errors = array();
?>