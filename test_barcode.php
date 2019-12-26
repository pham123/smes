<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('./config.php');
require('./function/MysqliDb.php');
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$code = $_POST['code'];
$newDB->where('BarcodetestsCode', $code);
$barcode = $newDB->getOne('barcodetests');
if($barcode){
    echo 'code exists';
    echo 'second line';
    echo "\r\n";
    echo 'second line';
}else{
    echo 'not found';
}
?>
