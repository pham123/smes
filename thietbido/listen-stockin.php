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
	$StockInput_id = $data['StockInputsId'];
	//CREATE NEW EXPORT HISTORY
	if(isset($_POST["saveBtn"])) {
		$StockInputData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'FromId' => $data['FromId'],
			'ToId' => $data['ToId'],
			'StockInputsType' => $data['StockInputsType'],
			'StockInputsDate' => $data['StockInputsDate'],
			'StockInputsNo' => $data['StockInputsNo'],
		];
	}
	if(isset($_POST["submitBtn"])) {
		$StockInputData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'FromId' => $data['FromId'],
			'ToId' => $data['ToId'],
			'StockInputsType' => $data['StockInputsType'],
			'StockInputsDate' => $data['StockInputsDate'],
            'StockInputsNo' => $data['StockInputsNo'],
            'StockInputsStatus' => 1
		];
	}
	$newDB->where('StockInputsId', $StockInput_id);
    $newDB->update('StockInputs', $StockInputData);


	$newDB->where('StockInputsId', $StockInput_id);
	$newDB->delete('StockInputitems');
	foreach($data['ProductsId'] as $index => $id){
		if($id){
			$itemData = [
				'StockInputsId' => $StockInput_id,
				'ProductsId' => $id,
				'StockInputItemsUnitPrice' => $data['StockInputItemsUnitPrice'][$index]?$data['StockInputItemsUnitPrice'][$index]:0,
				'StockInputItemsQty' => $data['StockInputItemsQty'][$index]?$data['StockInputItemsQty'][$index]:0,
				'StockInputItemsWo' => $data['StockInputItemsWo'][$index]?$data['StockInputItemsWo'][$index]:'',
				'StockInputItemsRemark' => $data['StockInputItemsRemark'][$index]?$data['StockInputItemsRemark'][$index]:''
			];
            $newDB->insert('StockInputitems', $itemData);
		}
	}
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:Stockin.php');