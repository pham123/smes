<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE Imports (
  ImportsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ImportsPO VARCHAR(50) NOT NULL,
  SuppliersId INT(9) NOT NULL,
  ImportsDate DATE,
  ImportsNote VARCHAR(50),
  ProductsId INT(9) NOT NULL,
  ProductsQty INT(9) NOT NULL,
  ProductsUnitPrice FLOAT NOT NULL
  );";
$sql[] = "CREATE TABLE Exports (
  ExportsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ExportsPO VARCHAR(50) NOT NULL,
  SectionId INT(9) NOT NULL,
  ExportsDate DATE,
  ExportsReceiver VARCHAR(50),
  ExportsNote VARCHAR(50),
  ProductsId INT(9) NOT NULL,
  ProductsQty INT(9) NOT NULL,
  ExportsReason VARCHAR(100)
  );";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

