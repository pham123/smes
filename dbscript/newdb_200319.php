<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('../config.php');
include('../function/db_lib.php');

$oDB = new db();
$sql[]="ALTER TABLE StockInputItems ADD StockInputItemsUnitPrice int(9);";
$sql[]="ALTER TABLE StockOutputItems ADD StockOutputItemsUnitPrice int(9);";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

