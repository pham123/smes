<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "ALTER TABLE `labellist` 
ADD UNIQUE INDEX `LabelListValue_UNIQUE` (`LabelListValue` ASC) VISIBLE;
;";

$sql[] = "ALTER TABLE `labelhistory` 
CHANGE COLUMN `ProductsId` `ProductsId` INT(6) NULL ;";


for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;