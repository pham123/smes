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
	$StockOutput_id = $data['StockOutputsId'];
	//CREATE NEW EXPORT HISTORY
	if(isset($_POST["saveBtn"])) {
		$StockOutputData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'FromId' => $data['FromId'],
			'ToId' => $data['ToId'],
			'StockOutputsType' => $data['StockOutputsType'],
			'StockOutputsDate' => $data['StockOutputsDate'],
			'StockOutputsNo' => $data['StockOutputsNo'],
		];
	}
	if(isset($_POST["submitBtn"])) {
		$StockOutputData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'FromId' => $data['FromId'],
			'ToId' => $data['ToId'],
			'StockOutputsType' => $data['StockOutputsType'],
			'StockOutputsDate' => $data['StockOutputsDate'],
            'StockOutputsNo' => $data['StockOutputsNo'],
            'StockOutputsStatus' => 1
		];
	}
	if(array_key_exists('PurchasesComment', $data)){
		$StockOutputData['PurchasesComment'] = $data['PurchasesComment'];
	}
	$newDB->where('StockOutputsId', $StockOutput_id);
    $newDB->update('StockOutputs', $StockOutputData);


	$newDB->where('StockOutputsId', $StockOutput_id);
	$newDB->delete('StockOutputitems');
	foreach($data['ProductsId'] as $index => $id){
		if($id){
			$itemData = [
				'StockOutputsId' => $StockOutput_id,
				'ProductsId' => $id,
				'StockOutputItemsUnitPrice' => $data['StockOutputItemsUnitPrice'][$index]?$data['StockOutputItemsUnitPrice'][$index]:0,
				'StockOutputItemsQty' => $data['StockOutputItemsQty'][$index]?$data['StockOutputItemsQty'][$index]:0,
				'StockOutputItemsWo' => $data['StockOutputItemsWo'][$index]?$data['StockOutputItemsWo'][$index]:'',
				'StockOutputItemsRemark' => $data['StockOutputItemsRemark'][$index]?$data['StockOutputItemsRemark'][$index]:''
			];
            $newDB->insert('StockOutputitems', $itemData);
		}
	}
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:Stockout.php');