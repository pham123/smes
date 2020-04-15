<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('../config.php');
include('../function/db_lib.php');

$oDB = new db();
$sql[] = "CREATE TABLE Cashgroups (
    CashgroupsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    CashgroupsCode varchar(30),
    CashgroupsName varchar(100),
    CashgroupsSecondName varchar(100),
    CashgroupsUnit varchar(50),
    CashgroupsFrequency int(6),
    CashgroupsBudget float
);";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

