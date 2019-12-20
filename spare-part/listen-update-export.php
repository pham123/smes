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
	//CHECK ID IS VALID
	if (!isset($_GET['id'])) {

		echo 'Empty export id';
		exit();

	} else if (!ctype_digit($_GET['id'])) {

		echo 'Invalid export id';
		exit();

	} else {
		$export_id = (int)$_GET['id'];
		
		$text = '';

		foreach ($_POST as $key => $value) {
			if ($key=='action'||$key=='target'||$key=='ExportsId') {
			
			}else{
				if($value){
					$text = $text.$key." = '".$value."',";
				}
			}
		}
		$text = rtrim($text, ',');
		
		$update_sql = "Update Exports Set ".$text."
					  Where ExportsId = ".$export_id;
		
		// echo $update_sql;
		// return;

		$oDB ->query($update_sql);

		
		$_SESSION['last'] = $export_id;
		
		$oDB = Null;

		header('Location:export-history.php');
	}
	
}else{
	header('Location:../404.html');
}

