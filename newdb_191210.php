<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE Processes (
  ProcessesId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ProcessesName VARCHAR(50) NOT NULL UNIQUE,
  ProcessesDescription VARCHAR(50),
  MaterialTypesCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  MaterialTypesUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  );";
$sql[] = "CREATE TABLE Makers (
  MakersId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  MakersName VARCHAR(50) NOT NULL UNIQUE,
  MakersDescription VARCHAR(50),
  MakersCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  MakersUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  );";
$sql[] = "CREATE TABLE ClassifiedMaterials (
  ClassifiedMaterialsId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ClassifiedMaterialsName VARCHAR(50) NOT NULL UNIQUE,
  ClassifiedMaterialsDescription VARCHAR(50),
  ClassifiedMaterialsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  ClassifiedMaterialsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  );";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

