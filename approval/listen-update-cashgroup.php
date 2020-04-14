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
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//CHECK ID IS VALID
	if (!isset($_GET['id'])) {

		return 'Empty cashgroup id';

	} else if (!ctype_digit($_GET['id'])) {

		$errors[] = 'Invalid cashgroup id';
		return;

	} else {
		$cashgroup_id = (int)$_GET['id'];
		
		$text = '';

		foreach ($_POST as $key => $value) {
			if ($key=='action'||$key=='target'||$key=='ProductsId') {
			
			}else{
			$text = $text.$key." = '".$value."',";
			}
		}
		$text = rtrim($text, ',');
		
		$update_sql = "Update cashgroups Set ".$text."
					  Where CashgroupsId = ".$cashgroup_id;
		
		// echo $update_sql;

		$oDB ->query($update_sql);

		
		$_SESSION['last'] = $cashgroup_id;
		
		$oDB = Null;
		$products = Null;

		header('Location:cashgroup.php');
	}
	
}else{
	header('Location:../404.html');
}


