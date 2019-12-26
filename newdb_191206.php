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

$sql[] = "ALTER TABLE Products
  ADD MaterialTypesId INT(6) UNSIGNED DEFAULT 1
    AFTER ModelsId;";

$sql[] = "CREATE TABLE Boms (
    BomsId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    BomsParentId INT(6) UNSIGNED NOT NULL DEFAULT 0,
    BomsPath VARCHAR(100) NOT NULL DEFAULT 'S',
    ProductsId INT(6) NOT NULL,
    BomsQty INT(6),
    ProcessesId INT(6) DEFAULT 0,
    MakersId INT(6) DEFAULT 0,
    ClassifiedMaterialsId INT(6) DEFAULT 0,
    MachinesId INT(6) DEFAULT 0,
    BomsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    BomsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

