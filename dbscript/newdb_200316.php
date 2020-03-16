<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('../config.php');
include('../function/db_lib.php');

$oDB = new db();
$sql[] = "CREATE TABLE StockOutputItemLogs (
    id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    StockOutputItemsId INT(9) NOT NULL,
    StockOutputItemsCartQty INT(6),
    StockOutputItemsQty INT(6),
    StockOutputItemsRemark VARCHAR(100),
    UsersId INT(9),
    CreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    UpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";
$sql[]="ALTER TABLE processdailyhistory ADD ProcessDailyHistoryManpow float;";
$sql[]="ALTER TABLE processdailyhistory ADD ProcessDailyHistoryUcode VARCHAR(20);";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

