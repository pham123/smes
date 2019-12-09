<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "ALTER TABLE products
  ADD ProductsSize VARCHAR(50) AFTER ProductsNumber,
  ADD ProductsNet FLOAT AFTER ProductsSize,
  ADD ProductsGloss FLOAT AFTER ProductsNet,
  ADD ProductsMaterial VARCHAR(50) AFTER ProductsGloss,
  ADD ProductsUnit VARCHAR(50) AFTER ProductsMaterial;";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

