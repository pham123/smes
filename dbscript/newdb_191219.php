<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE employees (
  EmployeesId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  EmployeesName VARCHAR(50) NOT NULL,
  EmployeesFirstName VARCHAR(50),
  EmployeesLastName VARCHAR(50),
  EmployeesCode VARCHAR(10) UNIQUE,
  EmployeesPosition VARCHAR(50),
  EmployeesInformation VARCHAR(100),
  DivisionsId INT(6) UNSIGNED,
  TeamsId INT(6) UNSIGNED,
  SectionId INT(6) UNSIGNED,
  EmployeesStatus TINYINT UNSIGNED DEFAULT 1,
  EmployeesEmail VARCHAR(30) UNIQUE NOT NULL,
  EmployeesImage VARCHAR(20),
  EmployeesCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  EmployeesUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  );";
$sql[] = "CREATE TABLE positions (
    PositionsId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    PositionsName VARCHAR(50) NOT NULL UNIQUE
    );";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

