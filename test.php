<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$user = New users();
$user->id = 1;
$user->acess();
echo $user->access;


echo '1';
