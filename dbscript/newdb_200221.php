<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE ProcessDailyHistory (
    ProcessDailyHistoryId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ProcessDailyHistoryDate Date,
    ProductsId int(9) NOT NULL,
    ProcessDailyHistoryMold VARCHAR(100),
    MachinesId INT(9) unsigned,
    TraceStationId INT(9) unsigned,
    PeriodId int(9),
    ProcessDailyHistoryOk INT(6),
    ProcessDailyHistoryNg INT(6),
    ProcessDailyHistoryIdletime INT(6)
    );";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

