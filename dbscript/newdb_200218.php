<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "ALTER TABLE employees DROP TeamsId;";
$sql[] = "ALTER TABLE employees DROP DivisionsId;";
$sql[] = "ALTER TABLE section ADD COLUMN DivisionsId INT(9) AFTER SectionOption;";
$sql[] = "ALTER TABLE section ADD COLUMN TeamsId INT(9) AFTER SectionOption;";
$sql[] = "ALTER TABLE section ADD COLUMN CompanyId INT(9) AFTER SectionOption;";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

