<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE ProcessNgDetail (
    ProcessNgDetailId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ProcessDailyHistoryId int(9) NOT NULL,
    DefectListId int(9) NOT NULL,
    ProcessNgDetailQty INT(6)
    );";
$sql[] = "CREATE TABLE ProcessIdleDetail (
    ProcessIdleDetailId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ProcessDailyHistoryId int(9) NOT NULL,
    IdleId int(9) NOT NULL,
    ProcessIdleDetailAmount INT(6)
    );";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

