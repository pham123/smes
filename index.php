<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
header("Location: login.php");
// include('function/db_lib.php');

// $oDB = new db();

// var_dump($_SESSION);