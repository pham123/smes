<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/MysqliDb.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

// var_dump($_POST);
// exit();
// Ghi thông tin vào database

$newDB->insert('cashgroups', [
	'CashgroupsName' => $_POST['CashgroupsName'],
	'CashgroupsSecondName' => $_POST['CashgroupsSecondName'],
	'CashgroupsCode' => $_POST['CashgroupsCode'],
	'CashgroupsUnit' => $_POST['CashgroupsUnit'],
	'CashgroupsFrequency' => $_POST['CashgroupsFrequency'],
	'CashgroupsBudget' => $_POST['CashgroupsBudget']
]);
        
$newDB = Null;

header('Location:cashgroup.php');