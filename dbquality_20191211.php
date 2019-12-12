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

$sql[]="ALTER TABLE `labellist` 
CHANGE COLUMN `UsersId` `UsersId` INT(6) NULL ;";

$sql[]="ALTER TABLE `smes`.`boms` 
ADD COLUMN `BomListId` INT(6) NULL AFTER `BomsUpdateDate`;
";

$sql[] = "CREATE TABLE BomList (
    BomListId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ProductsId INT(6) NOT NULL,
    BomListName VARCHAR(50),
    BomListCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    BomListUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";

$sql[] = "INSERT INTO `smes`.`bomlist` (`ProductsId`, `BomListName`) VALUES ('1', 'ABC');";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;