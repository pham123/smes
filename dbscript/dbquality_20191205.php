<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE QualityIssueType (
    QualityIssueTypeId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    QualityIssueTypeName VARCHAR(30),
    QualityIssueTypeOption INT(2),
    QualityIssueTypeCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    QualityIssueTypeUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";

$sql[] = "INSERT INTO QualityIssueType(`QualityIssueTypeName`,`QualityIssueTypeOption`)
VALUES ('Process',1)";
$sql[] = "INSERT INTO QualityIssueType(`QualityIssueTypeName`,`QualityIssueTypeOption`)
VALUES ('Customer',1)";
$sql[] = "INSERT INTO QualityIssueType(`QualityIssueTypeName`,`QualityIssueTypeOption`)
VALUES ('Supplier',1)";

$sql[] = "CREATE TABLE QualityIssuelist (
    QualityIssuelistId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    QualityIssuelistTitle VARCHAR(255),
    QualityIssuelistDate DATE,
    SupplyChainObjectId INT(6),
    ProductsId INT(6),
    QualityIssuelistCreator INT(6),
    QualityIssuelistLotNo VARCHAR(20),
    QualityIssuelistProductionDate DATE,
    QualityIssuelistDefectiveContent TEXT,
    QualityIssuelistLotQuantity INT(6),
    QualityIssuelistNgQuantity INT(6),
    QualityIssuelistTimesOccurs INT(6),
    QualityIssuelistDocNo VARCHAR(20),
    QualityIssuelistDueDate DATE,
    QualityIssuelistFinishDate DATE,
    QualityIssuelistRootCause TEXT,
    QualityIssuelistAction TEXT,
    UsersId INT(6),
    QualityIssuelistStatus INT(6),
    QualityIssuelistOption INT(2),
    QualityIssuelistCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    QualityIssuelistUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";


$sql[] = "CREATE TABLE SupplyChainType (
    SupplyChainTypeId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    SupplyChainTypeName VARCHAR(30),
    SupplyChainTypeOption INT(2),
    SupplyChainTypeCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    SupplyChainTypeUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";

$sql[] = "INSERT INTO SupplyChainType(`SupplyChainTypeName`,`SupplyChainTypeOption`)
VALUES ('Customer',1)";
$sql[] = "INSERT INTO SupplyChainType(`SupplyChainTypeName`,`SupplyChainTypeOption`)
VALUES ('Supplier',1)";
$sql[] = "INSERT INTO SupplyChainType(`SupplyChainTypeName`,`SupplyChainTypeOption`)
VALUES ('Internal',1)";

$sql[] = "CREATE TABLE SupplyChainObject (
    SupplyChainObjectId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    SupplyChainTypeId INT(6),
    SupplyChainObjectName VARCHAR(30),
    SupplyChainObjectOption INT(2),
    SupplyChainObjectCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    SupplyChainObjectUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";

$sql[] = "INSERT INTO SupplyChainObject(`SupplyChainObjectName`,`SupplyChainObjectOption`,`SupplyChainTypeId`)
VALUES ('Customer',1,1)";



for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;