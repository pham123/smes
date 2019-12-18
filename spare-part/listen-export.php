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
	foreach($data['ProductsId'] as $index => $id){
		if($id){
			//UPDATE PRODUCT STOCK
			$newDB->where('ProductsId', $id);
			$c_product = $newDB->getOne('products');
			$stock = $c_product['ProductsStock']?($c_product['ProductsStock'] - $data['ProductsQty'][$index]):$data['ProductsQty'][$index];

			$newDB->where('ProductsId', $id);
			$newDB->update('products', ['ProductsStock' => $stock]);

			//CREATE NEW EXPORT HISTORY
			$exportData = [
				'ExportsPO' => $data['ExportsPO'],
				'SectionId' => $data['SectionId'],
				'ExportsDate' => $data['ExportsDate'],
				'ProductsId' => $id,
				'ProductsQty' => $data['ProductsQty'][$index],
				'ExportsReason' => $data['ExportsReason'][$index]
			];
			if(array_key_exists('ExportsReceiver', $data)){
				$exportData['ExportsReceiver'] = $data['ExportsReceiver'];
			}
			if(array_key_exists('ExportsNote', $data)){
				$exportData['ExportsNote'] = $data['ExportsNote'];
			}
			$newDB->insert('exports', $exportData);
		}
	}
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:index.php');