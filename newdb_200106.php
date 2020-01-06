<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "ALTER TABLE Imports ADD COLUMN ImportsDocNo VARCHAR(50) UNIQUE AFTER ImportsPO;";
$sql[] = "ALTER TABLE Imports ADD COLUMN ImportsStatus TINYINT(1) DEFAULT 0 AFTER ImportsDocNo;";
$sql[] = "ALTER TABLE Imports ADD COLUMN user_id INTEGER DEFAULT 0 AFTER ImportsStatus;";
$sql[] = "ALTER TABLE Exports CHANGE `ExportsPO` `ExportsDocNo` VARCHAR(50) UNIQUE;";
$sql[] = "ALTER TABLE Exports ADD COLUMN ExportsStatus TINYINT(1) DEFAULT 0 AFTER ExportsDocNo;";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

