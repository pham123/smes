<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$pagetitle = $user->module;
$oDB = new db();

$column = safe($_POST['name']);
$value = safe($_POST['value']);
$id = safe($_POST['id']);

$oDB->update('QualityIssuelist',$column." ='".$value."'" ,'QualityIssuelistId='.$id);
$oDB=Null;
