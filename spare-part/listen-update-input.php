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

		echo 'Empty input id';
		exit();

	} else if (!ctype_digit($_GET['id'])) {

		echo 'Invalid input id';
		exit();

	} else {
		$input_id = (int)$_GET['id'];
		
		$text = '';

		//old input
		$newDB->where('InputsId', $input_id);
		$oldInput = $newDB->getOne('Inputs');
		$old_product_id = $oldInput['ProductsId'];
		$newDB->where('ProductsId', $old_product_id);
		$old_product = $newDB->getOne('Products');
		
		foreach ($_POST as $key => $value) {
			if ($key=='action'||$key=='target'||$key=='InputsId') {
				
			}else{
				if($value){
					$text = $text.$key." = '".$value."',";
				}
			}
		}
		$text = rtrim($text, ',');
		
		$update_sql = "Update Inputs Set ".$text."
		Where InputsId = ".$input_id;
		
		// echo $update_sql;
		
		$oDB ->query($update_sql);
		//update stock
		if($old_product_id == $_POST['ProductsId']){
			$newstock = $old_product['ProductsStock'] - $oldInput['ProductsQty'] + intval($_POST['ProductsQty']);

			$newDB->where('ProductsId', $old_product_id);
			$newDB->update('Products', ['ProductsStock' => $newstock]);
		}else{
			$newstock1 = $old_product['ProductsStock'] - $oldInput['ProductsQty'];
			$newDB->where('ProductsId', $old_product_id);
			$newDB->update('Products', ['ProductsStock' => $newstock1]);

			$newDB->where('ProductsId', $_POST['ProductsId']);
			$new_product = $newDB->getOne('Products');
			$newstock2 = $new_product['ProductsStock'] + $_POST['ProductsQty'];

			$newDB->where('ProductsId', $_POST['ProductsId']);
			$newDB->update('Products', ['ProductsStock' => $newstock2]);
		}

		$newDB = null;
		$oDB = Null;

		header('Location:input-list.php');
	}
	
}else{
	header('Location:../404.html');
}

