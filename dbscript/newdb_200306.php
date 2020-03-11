<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE DocumentLineApproval (
    DocumentLineApprovalId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    DocumentId int(9) NOT NULL,
    UsersId int(9) NOT NULL,
    DocumentLineApprovalStatus tinyint
    );";
$sql[] = "ALTER table document ADD DocumentEmailList TEXT;";
$sql[] = "ALTER table document ADD DocumentSubmit tinyint;";
$sql[] = "ALTER table processidledetail ADD ProcessIdleDetailExplain varchar(255);";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

