<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE Bomlists (
  BomlistsId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ProductsId INT(6) NOT NULL DEFAULT 0,
  BomlistsInfo VARCHAR(50),
  BomsQty INT(6),
  ProcessesId INT(6) DEFAULT 0,
  MakersId INT(6) DEFAULT 0,
  ClassifiedMaterialsId INT(6) DEFAULT 0,
  MachinesId INT(6) DEFAULT 0
  );";
$sql[] = "ALTER TABLE Boms ADD BomlistsId INTEGER(6) NOT NULL DEFAULT 0 AFTER BomsParentId;";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

