<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "ALTER TABLE shift
ADD COLUMN ShiftStart TIME NOT NULL,
ADD COLUMN ShiftEnd TIME NOT NULL;";
$sql[] = "ALTER TABLE proplan
ADD COLUMN ShiftId int(9) NOT NULL after ProductsId;";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

