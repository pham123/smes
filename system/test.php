<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');

$user = New Users();
$user->id = 1;
$user->module = basename(dirname(__FILE__));
var_dump($user->acess());
