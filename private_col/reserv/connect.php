<?php
/**This function connects the scripts to the database*/
function Connect_Database(){
/**passes in an error massage in case of connection errors*/
$connect_error = 'sorry, we are experiencing down time';

//-------------------------------------------------------------------------------------------------------------------------------------------

//**takes 'hostname','username','password','database name'*/
$db = new mysqli('localhost','root','','nkl_loyalty_db') or die($connect_error);
echo $db -> connect_error; 

return $db;
//-------------------------------------------------------------------------------------------------------------------------------------------
}
?>