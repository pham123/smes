<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('../config.php');
include('../function/db_lib.php');

$oDB = new db();
$sql[] = "CREATE TABLE PurchasePaymentLineApproval (
    id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    PurchasePaymentsId int(9) NOT NULL,
    UsersId int(9) NOT NULL,
    LineStatus tinyint,
    LineComment varchar(255),
    ApprovalDate timestamp
    );";
$sql[] = "ALTER TABLE PurchasePayments ADD UsersId int(9);";
$sql[] = "ALTER TABLE PurchasePayments ADD PurchasePaymentsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP;";
$sql[] = "ALTER TABLE PurchasePayments ADD PurchasePaymentsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

