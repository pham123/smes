<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE Purchases (
    PurchasesId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UsersId INT(9) NOT NULL,
    RequestSectionId INT(9),
    ReceiveSectionId INT(9),
    TraceStationId INT(9),
    IsUrgent TINYINT(1) NOT NULL default 0,
    PurchasesDate DATE,
    PurchasesNo VARCHAR(50) NOT NULL,
    PurchasesComment TEXT,
    PurchasesStatus TINYINT(1) NOT NULL DEFAULT 0
    );";
$sql[] = "CREATE TABLE PurchaseItems (
    PurchaseItemsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    PurchasesId INT(9) NOT NULL,
    ProductsId INT(9) NOT NULL,
    ManufacturerCode VARCHAR(20),
    ManufacturerName VARCHAR(50),
    PurchasesQty INT(6) DEFAULT 0,
    PurchasesEta DATE,
    PurchasesRemark VARCHAR(100)
    );";
$sql[] = "ALTER TABLE products
ADD COLUMN ProductsColor VARCHAR(30);";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

