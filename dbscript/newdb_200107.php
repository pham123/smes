<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE ExistSpareParts (
    ExistSparePartsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ProductsId INT(6) NOT NULL,
    ExistSparePartsMonth VARCHAR(10) NOT NULL,
    ExistSparePartsQty INT(9) DEFAULT 0,
    ExistSparePartsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    ExistSparePartsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";
$sql[] = "ALTER TABLE SupplyChainObject MODIFY COLUMN SupplyChainObjectName VARCHAR(255);";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

