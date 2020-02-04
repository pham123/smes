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
	$import_id = $data['ImportsId'];
	//CREATE NEW IMPORT HISTORY
	if(isset($_POST["saveBtn"])) {
		$importData = [
			'ImportsPO' => $data['ImportsPO'],
			'ImportsDocNo' => $data['ImportsDocNo'],
			'SuppliersId' => $data['SuppliersId'],
			'ImportsDate' => $data['ImportsDate']
		];
	  }
	if(isset($_POST["importBtn"])) {
		$importData = [
			'ImportsPO' => $data['ImportsPO'],
			'ImportsDocNo' => $data['ImportsDocNo'],
			'SuppliersId' => $data['SuppliersId'],
			'ImportsDate' => $data['ImportsDate'],
			'ImportsStatus' => 1
		];

		$logs_content = 'imports '.$_SESSION[_site_]['username'].' create '.$import_id.' PO('.$importData['ImportsPO'].')'.' DocNo('.$importData['ImportsDocNo'].')'.' SuppliersId('.$importData['SuppliersId'].')'.' Date('.$importData['ImportsDate'].') ';
	}
	if(array_key_exists('ImportsNote', $data)){
		$importData['ImportsNote'] = $data['ImportsNote'];
	}

	$newDB->where('ImportsId', $import_id);
	$newDB->update('Imports', $importData);

	$newDB->where('ImportsId', $import_id);
	$newDB->delete('Inputs');
	foreach($data['ProductsId'] as $index => $id){
		if($id){
			//UPDATE PRODUCT STOCK
			if(isset($_POST["importBtn"])){
				$newDB->where('ProductsId', $id);
				$c_product = $newDB->getOne('products');
				$stock = $c_product['ProductsStock']?($c_product['ProductsStock'] + $data['ProductsQty'][$index]):$data['ProductsQty'][$index];
				
				$newDB->where('ProductsId', $id);
				$newDB->update('Products', ['ProductsStock' => $stock]);

				if($index == 0){
					$logs_content .= 'Products['.$id.','.$data['ProductsQty'][$index].','.str_replace(array('.', ','), '.' , $data['ProductsUnitPrice'][$index]).'] ';
				}else{
					$logs_content .= '['.$id.','.$data['ProductsQty'][$index].','.str_replace(array('.', ','), '.' , $data['ProductsUnitPrice'][$index]).'] ';
				}
			}
			
			
			$newDB->where('ImportsId', $import_id);
			$newDB->where('ProductsId', $id);
			$result = $newDB->delete('Inputs');

			//CREATE NEW INPUT

			$inputData = [
				'ImportsId' => $import_id,
				'ProductsId' => $id,
				'ProductsQty' => $data['ProductsQty'][$index],
				'ProductsUnitPrice' => str_replace(array('.', ','), '' , $data['ProductsUnitPrice'][$index])
			];
			$newDB->insert('Inputs', $inputData);


		}
	}

	if(isset($_POST["importBtn"])) {
		$logs_content .= 'file='.basename($_SERVER['PHP_SELF']);
		w_logs(__DIR__."\logs\\", $logs_content);
	}



}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:index.php');