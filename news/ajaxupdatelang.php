<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

$code = $_GET['code'];

$newDB->where('UsersId', $_SESSION[_site_]['userid']);
$newDB->update('users', ['UsersLang' => $code]);

$_SESSION[_site_]['userlang'] = $code;

return;

