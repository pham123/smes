<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('config.php');
require('function/db_lib.php');
$oDB = new db();

$sql = "Select * from Company";

$ketqua = $oDB-> fetchOne($sql);

foreach ($ketqua as $key => $value) {
    echo $key;
    echo "</Br>";
}
