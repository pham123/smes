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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = array_filter($_POST);
	$GoodsOutput_id = $data['GoodsOutputsId'];
	//CREATE NEW EXPORT HISTORY
	if(isset($_POST["saveBtn"])) {
		$GoodsOutputData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'FromId' => $data['FromId'],
			'ToId' => $data['ToId'],
			'GoodsOutputsType' => $data['GoodsOutputsType'],
			'GoodsOutputsDate' => $data['GoodsOutputsDate'],
			'GoodsOutputsNo' => $data['GoodsOutputsNo'],
		];
	}
	if(isset($_POST["submitBtn"])) {
		$GoodsOutputData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'FromId' => $data['FromId'],
			'ToId' => $data['ToId'],
			'GoodsOutputsType' => $data['GoodsOutputsType'],
			'GoodsOutputsDate' => $data['GoodsOutputsDate'],
            'GoodsOutputsNo' => $data['GoodsOutputsNo'],
            'GoodsOutputsStatus' => 1
		];
	}
	if(array_key_exists('PurchasesComment', $data)){
		$GoodsOutputData['PurchasesComment'] = $data['PurchasesComment'];
	}
	$newDB->where('GoodsOutputsId', $GoodsOutput_id);
    $newDB->update('GoodsOutputs', $GoodsOutputData);


	$newDB->where('GoodsOutputsId', $GoodsOutput_id);
	$newDB->delete('GoodsOutputitems');
	foreach($data['ProductsId'] as $index => $id){
		if($id){
			$itemData = [
				'GoodsOutputsId' => $GoodsOutput_id,
				'ProductsId' => $id,
				'GoodsOutputItemsUnitPrice' => $data['GoodsOutputItemsUnitPrice'][$index]?$data['GoodsOutputItemsUnitPrice'][$index]:0,
				'GoodsOutputItemsQty' => $data['GoodsOutputItemsQty'][$index]?$data['GoodsOutputItemsQty'][$index]:0,
				'GoodsOutputItemsWo' => $data['GoodsOutputItemsWo'][$index]?$data['GoodsOutputItemsWo'][$index]:'',
				'GoodsOutputItemsRemark' => $data['GoodsOutputItemsRemark'][$index]?$data['GoodsOutputItemsRemark'][$index]:''
			];
            $newDB->insert('GoodsOutputitems', $itemData);
		}
	}
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:goodsout.php');