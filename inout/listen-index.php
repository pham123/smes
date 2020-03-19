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
// $newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = array_filter($_POST);
	print_r($data);
	if($data['StockType'] == '1'){
		header('Location: newgoodsin.php?materialtypeid='.$data['MaterialTypesId']);
	}else if($data['StockType'] == '2'){
		header('Location: newgoodsout.php?materialtypeid='.$data['MaterialTypesId']);
	}
}else{
	header('Location:../404.html');
}

// $newDB = Null;
