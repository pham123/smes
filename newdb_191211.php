<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE Categories (
  CategoriesId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  CategoriesName VARCHAR(30) NOT NULL
  );";
$sql[] = "ALTER TABLE Products 
ADD ProductsEngName VARCHAR(50) AFTER ProductsNumber, 
ADD ProductsStock INT(6) AFTER ProductsUnit,
ADD ProductsSafetyStk VARCHAR(10) AFTER ProductsStock,
ADD ProductsMinimumStk VARCHAR(10) AFTER ProductsSafetyStk,
ADD ProductsLocation VARCHAR(10) AFTER ProductsMinimumStk,
ADD ProductsCategory int(6) AFTER ProductsLocation,
ADD ProductsOrderStatus VARCHAR(20) AFTER ProductsCategory,
ADD ProductsMachine VARCHAR(50) AFTER ProductsOrderStatus,
ADD ProductsNote VARCHAR(50) AFTER ProductsMachine;";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

