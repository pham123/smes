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
	$purchase_id = $data['PurchasesId'];
	//CREATE NEW EXPORT HISTORY
	if(isset($_POST["saveBtn"])) {
		$purchaseData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'RequestSectionId' => $data['RequestSectionId'],
			'ReceiveSectionId' => $data['ReceiveSectionId'],
			'TraceStationId' => $data['TraceStationId'],
			'IsUrgent' => $data['IsUrgent'],
			'PurchasesDate' => $data['PurchasesDate'],
			'PurchasesNo' => $data['PurchasesNo'],
		];
	}
	if(isset($_POST["submitBtn"])) {
		$purchaseData = [
            'UsersId' => $_SESSION[_site_]['userid'],
			'RequestSectionId' => $data['RequestSectionId'],
			'ReceiveSectionId' => $data['ReceiveSectionId'],
			'TraceStationId' => $data['TraceStationId'],
			'IsUrgent' => $data['IsUrgent'],
			'PurchasesDate' => $data['PurchasesDate'],
            'PurchasesNo' => $data['PurchasesNo'],
            'PurchasesStatus' => 1
		];
	}
	if(array_key_exists('PurchasesComment', $data)){
		$purchaseData['PurchasesComment'] = $data['PurchasesComment'];
	}
	$newDB->where('PurchasesId', $purchase_id);
    $newDB->update('Purchases', $purchaseData);


	$newDB->where('PurchasesId', $purchase_id);
	$newDB->delete('purchaseitems');
	foreach($data['ProductsId'] as $index => $id){
		if($id){
			$itemData = [
				'PurchasesId' => $purchase_id,
				'ProductsId' => $id,
				'ManufacturerCode' => $data['ManufacturerCode'][$index]?$data['ManufacturerCode'][$index]:'',
				'ManufacturerName' => $data['ManufacturerName'][$index]?$data['ManufacturerName'][$index]:'',
				'PurchasesQty' => $data['PurchasesQty'][$index]?$data['PurchasesQty'][$index]:0,
				'PurchasesEta' => $data['PurchasesEta'][$index]?$data['PurchasesEta'][$index]:null,
				'PurchasesRemark' => $data['PurchasesRemark'][$index]?$data['PurchasesRemark'][$index]:''
			];
            $newDB->insert('purchaseitems', $itemData);
		}
	}
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:index.php');