<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$oDB = new db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	$text = '';
	$columns = '';

	foreach ($_POST as $key => $value) {
		if($value){
			$columns .= $key.',';
			$text .= "'".safe(trim($value))."',";
		}

	}
	$columns = rtrim($columns, ',');
	$text = rtrim($text, ',');
	
	$create_sql = "INSERT INTO boms (".$columns.") VALUES(".$text.")";
	
	// echo $create_sql;

	$oDB ->query($create_sql);

	$last_row = $oDB->sl_one('boms', 'BomsId=(select max(BomsId) from boms)');

	//update BOM path
	if($last_row && $last_row['BomsParentId'] != 0){
		$parent_row = $oDB->sl_one('boms', 'BomsId = '.$last_row['BomsParentId']);
		
		$update_bom_path_sql = "UPDATE boms SET BomsPath='".$parent_row['BomsPath']."-".$last_row['BomsId']."' WHERE BomsId = ".$last_row['BomsId'];
		$oDB->query($update_bom_path_sql);
	}




	
	// $_SESSION['last'] = $product_id;
	
	$oDB = Null;

	header('Location:bom_list.php');
	
}else{
	header('Location:../404.html');
}