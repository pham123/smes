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
	$stockoutput_id = $data['StockOutputsId'];
	//CREATE NEW EXPORT HISTORY
	if(isset($_POST["saveBtn"])) {
		$stockoutputData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'FromId' => $data['FromId'],
			'ToId' => $data['ToId'],
			'ModelsId' => $data['ModelsId'],
			'StockOutputsType' => $data['StockOutputsType'],
			'StockOutputsDate' => $data['StockOutputsDate'],
			'StockOutputsNo' => $data['StockOutputsNo'],
		];
	}
	if(isset($_POST["submitBtn"])) {
		$stockoutputData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'FromId' => $data['FromId'],
			'ToId' => $data['ToId'],
			'ModelsId' => $data['ModelsId'],
			'StockOutputsType' => $data['StockOutputsType'],
			'StockOutputsDate' => $data['StockOutputsDate'],
            'StockOutputsNo' => $data['StockOutputsNo'],
            'StockOutputsStatus' => 1
		];
	}
	if(array_key_exists('PurchasesComment', $data)){
		$stockoutputData['PurchasesComment'] = $data['PurchasesComment'];
	}
	$newDB->where('StockOutputsId', $stockoutput_id);
    $newDB->update('stockoutputs', $stockoutputData);


	$newDB->where('StockOutputsId', $stockoutput_id);
	$newDB->delete('stockoutputitems');
	foreach($data['ProductsId'] as $index => $id){
		if($id){
			$itemData = [
				'StockOutputsId' => $stockoutput_id,
				'ProductsId' => $id,
				'StockOutputItemsCartQty' => $data['StockOutputItemsCartQty'][$index]?$data['StockOutputItemsCartQty'][$index]:0,
				'StockOutputItemsQty' => $data['StockOutputItemsQty'][$index]?$data['StockOutputItemsQty'][$index]:0,
				'StockOutputItemsProcess' => $data['StockOutputItemsProcess'][$index]?$data['StockOutputItemsProcess'][$index]:'',
				'StockOutputItemsMold' => $data['StockOutputItemsMold'][$index]?$data['StockOutputItemsMold'][$index]:'',
				'StockOutputItemsWo' => $data['StockOutputItemsWo'][$index]?$data['StockOutputItemsWo'][$index]:'',
				'StockOutputItemsRemark' => $data['StockOutputItemsRemark'][$index]?$data['StockOutputItemsRemark'][$index]:''
			];
            $newDB->insert('stockoutputitems', $itemData);
		}
	}
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:stockout.php');