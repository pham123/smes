<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('../config.php');
include('../function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE StockOutputs (
    StockOutputsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UsersId INT(9) NOT NULL,
    ModelsId INT(9),
    FromId INT(9),
    ToId INT(9),
    StockOutputsDate DATE,
    StockOutputsNo VARCHAR(50) not null,
    StockOutputsType VARCHAR(50),
    StockOutputsBks VARCHAR(50),
    StockOutputsStatus TINYINT(1) NOT NULL DEFAULT 0,
    StockOutputsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    StockOutputsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";
$sql[] = "CREATE TABLE StockOutputItems (
    StockOutputItemsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    StockOutputsId INT(9) NOT NULL,
    ProductsId INT(9) NOT NULL,
    StockOutputItemsWo VARCHAR(100),
    StockOutputItemsCartQty INT(6) DEFAULT 0,
    StockOutputItemsQty INT(6) DEFAULT 0,
    StockOutputItemsRemark VARCHAR(100)
    );";
$sql[]="ALTER TABLE stockoutputs ADD StockOutputsTime VARCHAR(100);";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

