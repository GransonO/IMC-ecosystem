<?php
$reg_date = date('Y-m-d H:i:s');	
$teacher_id = date('mHYis');
$sha1_no = sha1($teacher_id);
echo $sha1_no;
?>