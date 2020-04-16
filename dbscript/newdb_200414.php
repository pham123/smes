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
$sql[] = "CREATE TABLE PurchasePayments (
    PurchasePaymentsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    CashgroupsId int(9),
    IsUrgent tinyint(1) default 0,
    PurchasePaymentsNum int(6),
    PurchasePaymentsTitle varchar(255),
    PurchasePaymentsDate date,
    PurchasePaymentsReceiveDate date,
    PurchasePaymentsAmount int(11),
    PurchasePaymentsCurrency varchar(10),
    PurchaseOrdersId int(9),
    PurchasePaymentsContent text
);";
$sql[] = "ALTER TABLE modules ADD ModulesForcedLine varchar(30);";
$sql[] = "ALTER TABLE document ADD DocumentNumber varchar(30);";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

