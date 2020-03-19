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
	$GoodsInput_id = $data['GoodsInputsId'];
	//CREATE NEW EXPORT HISTORY
	if(isset($_POST["saveBtn"])) {
		$GoodsInputData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'FromId' => $data['FromId'],
			'ToId' => $data['ToId'],
			'GoodsInputsBks' => $data['GoodsInputsBks'],
			'GoodsInputsType' => $data['GoodsInputsType'],
			'GoodsInputsDate' => $data['GoodsInputsDate'],
			'GoodsInputsNo' => $data['GoodsInputsNo'],
		];
	}
	if(isset($_POST["submitBtn"])) {
		$GoodsInputData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'FromId' => $data['FromId'],
			'ToId' => $data['ToId'],
			'GoodsInputsBks' => $data['GoodsInputsBks'],
			'GoodsInputsType' => $data['GoodsInputsType'],
			'GoodsInputsDate' => $data['GoodsInputsDate'],
            'GoodsInputsNo' => $data['GoodsInputsNo'],
            'GoodsInputsStatus' => 1
		];
	}
	$newDB->where('GoodsInputsId', $GoodsInput_id);
    $newDB->update('GoodsInputs', $GoodsInputData);


	$newDB->where('GoodsInputsId', $GoodsInput_id);
	$newDB->delete('GoodsInputitems');
	foreach($data['ProductsId'] as $index => $id){
		if($id){
			$itemData = [
				'GoodsInputsId' => $GoodsInput_id,
				'ProductsId' => $id,
				'GoodsInputItemsUnitPrice' => $data['GoodsInputItemsUnitPrice'][$index]?$data['GoodsInputItemsUnitPrice'][$index]:0,
				'GoodsInputItemsQty' => $data['GoodsInputItemsQty'][$index]?$data['GoodsInputItemsQty'][$index]:0,
				'GoodsInputItemsWo' => $data['GoodsInputItemsWo'][$index]?$data['GoodsInputItemsWo'][$index]:'',
				'GoodsInputItemsRemark' => $data['GoodsInputItemsRemark'][$index]?$data['GoodsInputItemsRemark'][$index]:''
			];
            $newDB->insert('GoodsInputitems', $itemData);
		}
	}
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:goodsin.php');