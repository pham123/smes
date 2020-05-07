<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

// $newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
if($_POST['action'] == 'deletefile'){
    $file = $_POST['file'];
    if(is_file($file))
          unlink($file); // delete file
}
return;
?>