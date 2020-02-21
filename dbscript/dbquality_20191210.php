<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE LabelPattern (
    LabelPatternId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TraceStationId INT(6) NOT NULL,
    ProductsId INT(6) NOT NULL,
    LabelPatternValue VARCHAR(50) NOT NULL,
    LabelPatternCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    LabelPatternUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";

$sql[] = "INSERT INTO LabelPattern(`TraceStationId`,`ProductsId`,`LabelPatternValue`)
VALUES (1,1,'ABC123456-****-****')";


$sql[] = "CREATE TABLE LabelList (
    LabelListId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ProductsId INT(6) NOT NULL,
    UsersId INT(6) NOT NULL,
    LabelListValue VARCHAR(50) NOT NULL,
    LabelListCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    LabelListUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";


$sql[] = "CREATE TABLE LabelHistory (
    LabelHistoryId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TraceStationId INT(6) NOT NULL,
    ProductsId INT(6) NOT NULL,
    LabelListId INT(6),
    LabelHistoryQuantityOk INT(6),
    LabelHistoryQuantityNg INT(6),
    LabelHistoryLabelValue VARCHAR(50) NOT NULL,
    LabelHistoryCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    LabelHistoryUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";

$sql[]="ALTER TABLE `labellist` ADD COLUMN `LabelListMotherId` INT(6) NULL AFTER `LabelListValue`;";
$sql[]="ALTER TABLE `labelpattern` ADD COLUMN `LabelPatternPackingStandard` INT(6) NULL AFTER `LabelPatternValue`;";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;