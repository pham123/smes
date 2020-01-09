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
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//CHECK ID IS VALID
	if (!isset($_GET['id'])) {

		echo 'Empty output id';
		exit();

	} else if (!ctype_digit($_GET['id'])) {

		echo 'Invalid output id';
		exit();

	} else {
		$output_id = (int)$_GET['id'];
		
		$text = '';
		//old output
		$newDB->where('OutputsId', $output_id);
		$oldOutput = $newDB->getOne('Outputs');
		$old_product_id = $oldOutput['ProductsId'];
		$newDB->where('ProductsId', $old_product_id);
		$old_product = $newDB->getOne('Products');

		foreach ($_POST as $key => $value) {
			if ($key=='action'||$key=='target'||$key=='OutputsId') {
			
			}else{
				if($value){
					$text = $text.$key." = '".$value."',";
				}
			}
		}
		$text = rtrim($text, ',');
		
		$update_sql = "Update Outputs Set ".$text."
					  Where OutputsId = ".$output_id;

		$oDB ->query($update_sql);
		//update stock
		if($old_product_id == $_POST['ProductsId']){
			$newstock = $old_product['ProductsStock'] + $oldOutput['ProductsQty'] - $_POST['ProductsQty'];
			$newDB->where('ProductsId', $old_product_id);
			$newDB->update('Products', ['ProductsStock' => $newstock]);
		}else{
			$newstock1 = $old_product['ProductsStock'] + $oldOutput['ProductsQty'];
			$newDB->where('ProductsId', $old_product_id);
			$newDB->update('Products', ['ProductsStock' => $newstock1]);

			$newDB->where('ProductsId', $_POST['ProductsId']);
			$new_product = $newDB->getOne('Products');
			$newstock2 = $new_product['ProductsStock'] - $_POST['ProductsQty'];

			$newDB->where('ProductsId', $_POST['ProductsId']);
			$newDB->update('Products', ['ProductsStock' => $newstock2]);
		}
		
		$newDB = null;
		$oDB = Null;

		header('Location:output-list.php');
	}
	
}else{
	header('Location:../404.html');
}

