<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('../config.php');
include('../function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE StockInputs (
    StockInputsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UsersId INT(9) NOT NULL,
    ModelsId INT(9),
    FromId INT(9),
    ToId INT(9),
    StockInputsDate DATE,
    StockInputsNo VARCHAR(50) not null,
    StockInputsType VARCHAR(50),
    StockInputsBks VARCHAR(50),
    StockInputsStatus TINYINT(1) NOT NULL DEFAULT 0,
    StockInputsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    StockInputsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";
$sql[] = "CREATE TABLE StockInputItems (
    StockInputItemsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    StockInputsId INT(9) NOT NULL,
    ProductsId INT(9) NOT NULL,
    StockInputItemsWo VARCHAR(100),
    StockInputItemsCartQty INT(6) DEFAULT 0,
    StockInputItemsQty INT(6) DEFAULT 0,
    StockInputItemsRemark VARCHAR(100)
    );";
$sql[]="ALTER TABLE StockInputs ADD StockInputsTime VARCHAR(100);";
$sql[] = "CREATE TABLE StockInputItemLogs (
    id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    StockInputItemsId INT(9) NOT NULL,
    StockInputItemsCartQty INT(6),
    StockInputItemsQty INT(6),
    StockInputItemsRemark VARCHAR(100),
    UsersId INT(9),
    CreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    UpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";
$sql[] = "CREATE TABLE DocumentDetailLineApproval (
    DocumentDetailLineApprovalId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    DocumentDetailId int(9) NOT NULL,
    UsersId int(9) NOT NULL,
    DocumentDetailLineApprovalStatus tinyint,
    DocumentDetailLineApprovalComment varchar(255),
    DocumentDetailLineApprovalDate timestamp
    );";
$sql[]="ALTER TABLE documentdetail ADD UsersId int(9);";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

