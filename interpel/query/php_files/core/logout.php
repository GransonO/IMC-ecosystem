<?php
/**includes functions from the init file*/
include 'init.php';
session_unset();
session_destroy();
header('Location: ../../../ini/');
?>