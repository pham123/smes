<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "ALTER TABLE exports DROP COLUMN ProductsId;";
$sql[] = "ALTER TABLE exports DROP COLUMN ProductsQty;";
$sql[] = "ALTER TABLE imports DROP COLUMN ProductsId;";
$sql[] = "ALTER TABLE imports DROP COLUMN ProductsQty;";
$sql[] = "ALTER TABLE imports DROP COLUMN ProductsUnitPrice;";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

