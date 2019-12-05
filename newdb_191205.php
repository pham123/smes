<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE MaterialType (
    MaterialTypesId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    MaterialTypesName VARCHAR(50) NOT NULL UNIQUE,
    MaterialTypesCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    MaterialTypesUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql[] = "INSERT INTO MaterialType(`MaterialTypesId`,`MaterialTypesName`)
VALUES (1, 'Product')";

$sql[] = "INSERT INTO MaterialType(`MaterialTypesId`,`MaterialTypesName`)
VALUES (2, 'Material')";

$sql[] = "INSERT INTO MaterialType(`MaterialTypesId`,`MaterialTypesName`)
VALUES (3, 'Spare Part')";

$sql[] = "ALTER TABLE products
  ADD MaterialsType INT(1) UNSIGNED DEFAULT 1
    AFTER ModelsId;";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

