<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('../config.php');
include('../function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE GoodsInputs (
    GoodsInputsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UsersId INT(9) NOT NULL,
    ModelsId INT(9),
    FromId INT(9),
    ToId INT(9),
    GoodsInputsDate DATE,
    GoodsInputsNo VARCHAR(50) not null,
    GoodsInputsType VARCHAR(50),
    GoodsInputsBks VARCHAR(50),
    GoodsInputsStatus TINYINT(1) NOT NULL DEFAULT 0,
    GoodsInputsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    GoodsInputsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";
$sql[] = "CREATE TABLE GoodsInputItems (
    GoodsInputItemsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    GoodsInputsId INT(9) NOT NULL,
    ProductsId INT(9) NOT NULL,
    GoodsInputItemsWo VARCHAR(100),
    GoodsInputItemsUnitPrice INT(9) DEFAULT 0,
    GoodsInputItemsQty INT(6) DEFAULT 0,
    GoodsInputItemsRemark VARCHAR(100)
    );";
$sql[]="ALTER TABLE GoodsInputs ADD GoodsInputsTime VARCHAR(100);";
$sql[] = "CREATE TABLE GoodsOutputs (
    GoodsOutputsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UsersId INT(9) NOT NULL,
    FromId INT(9),
    ToId INT(9),
    GoodsOutputsDate DATE,
    GoodsOutputsNo VARCHAR(50) not null,
    GoodsOutputsType VARCHAR(50),
    GoodsOutputsBks VARCHAR(50),
    GoodsOutputsStatus TINYINT(1) NOT NULL DEFAULT 0,
    GoodsOutputsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    GoodsOutputsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";
$sql[] = "CREATE TABLE GoodsOutputItems (
    GoodsOutputItemsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    GoodsOutputsId INT(9) NOT NULL,
    ProductsId INT(9) NOT NULL,
    GoodsOutputItemsWo VARCHAR(100),
    GoodsOutputItemsUnitPrice INT(9) DEFAULT 0,
    GoodsOutputItemsQty INT(6) DEFAULT 0,
    GoodsOutputItemsRemark VARCHAR(100)
    );";
$sql[]="ALTER TABLE GoodsOutputs ADD GoodsOutputsTime VARCHAR(100);";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

