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
	
	$create_sql = "INSERT INTO ClassifiedMaterials (".$columns.") VALUES(".$text.")";

	$oDB ->query($create_sql);
	
	$oDB = Null;

	header('Location:classifiedmaterial_index.php');
	
}else{
	header('Location:../404.html');
}