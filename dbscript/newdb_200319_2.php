<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('../config.php');
include('../function/db_lib.php');

$oDB = new db();
$sql[]="ALTER TABLE StockInputs ADD StockInputsModule varchar(30);";
$sql[]="ALTER TABLE StockOutputs ADD StockOutputsModule varchar(30);";
$sql[]="ALTER TABLE modules ADD ModulesAssignMaterial varchar(30);";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

