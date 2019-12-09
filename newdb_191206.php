<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE MaterialTypes (
    MaterialTypesId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    MaterialTypesName VARCHAR(50) NOT NULL UNIQUE,
    MaterialTypesCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    MaterialTypesUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql[] = "INSERT INTO MaterialTypes(`MaterialTypesId`,`MaterialTypesName`)
VALUES (1, 'Product')";

$sql[] = "INSERT INTO MaterialTypes(`MaterialTypesId`,`MaterialTypesName`)
VALUES (2, 'Material')";

$sql[] = "INSERT INTO MaterialTypes(`MaterialTypesId`,`MaterialTypesName`)
VALUES (3, 'Spare Part')";

$sql[] = "ALTER TABLE products
  ADD MaterialTypesId INT(6) UNSIGNED DEFAULT 1
    AFTER ModelsId;";

$sql[] = "CREATE TABLE Boms (
    BomsId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    BomsParentId INT(6) UNSIGNED NOT NULL DEFAULT 0,
    BomsPath VARCHAR(100) NOT NULL DEFAULT 'S',
    BomsPartNo VARCHAR(30) NOT NULL,
    BomsPartName VARCHAR(50) NOT NULL,
    BomsSize VARCHAR(30),
    BomsNet FLOAT,
    BomsGloss FLOAT,
    BomsMaterial VARCHAR(50),
    BomsUnit VARCHAR(20),
    BomsQty INT(6) NOT NULL DEFAULT 0,
    BomsProcess VARCHAR(50),
    BomsMaker VARCHAR(30),
    BomsClassifiedMaterial VARCHAR(30),
    BomsMachine VARCHAR(30),
    BomsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    BomsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

