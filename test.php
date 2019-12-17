<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('config.php');
require('function/db_lib.php');
require('lang/en.php');
$pagetitle ="Login Page";
require('views/template-header.php');
require('function/template.php');
$oDB = new db();


echo $oDB->getcol('Users');