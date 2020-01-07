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
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);

$month=date('Y-m');

$newDB->where('ProductsOption', 4);
$spareparts = $newDB->get('Products');

foreach($spareparts as $key => $sp)
{
	//DELETE OLD SPARE PART DATA
	$newDB->where('ExistSparePartsMonth', $month);
	$newDB->where('ProductsId', $sp['ProductsId']);
	$newDB->delete('ExistSpareParts');

	//UPDATE NEW SPARE PART DATA
	$existData = [
		'ProductsId' => $sp['ProductsId'],
		'ExistSparePartsMonth' => $month,
		'ExistSparePartsQty' => $sp['ProductsStock']
	];

	$newDB->insert('ExistSpareParts', $existData);
}


$newDB = Null;
header('Location:tonkho_index.php');