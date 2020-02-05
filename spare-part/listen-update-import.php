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
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//CHECK ID IS VALID
	if (!isset($_GET['id'])) {

		echo 'Empty import id';
		exit();

	} else if (!ctype_digit($_GET['id'])) {

		echo 'Invalid import id';
		exit();

	} else {
		$import_id = (int)$_GET['id'];

		$newDB->where('ImportsId', $import_id);
		$old_i = $newDB->getOne('imports');
		
		$text = '';

		foreach ($_POST as $key => $value) {
			if ($key=='action'||$key=='target'||$key=='ImportsId') {
			
			}else{
				if($value){
					$text = $text.$key." = '".$value."',";
				}
			}
		}
		$text = rtrim($text, ',');
		
		$update_sql = "Update Imports Set ".$text."
					  Where ImportsId = ".$import_id;

		$newDB->where('ImportsId', $import_id);
		
		$oDB ->query($update_sql);
		// echo $update_sql;
		$new_i = $newDB->getOne('imports');
		$logs_content = 'imports '.$_SESSION[_site_]['username'].' update '.$import_id.' PO('.$old_i['ImportsPO'].'=>'.$new_i['ImportsPO'].')'.' DocNo('.$old_i['ImportsDocNo'].'=>'.$new_i['ImportsDocNo'].')'.' Date('.$old_i['ImportsDate'].'=>'.$new_i['ImportsDate'].')'.' SuppliersId('.$old_i['SuppliersId'].'=>'.$new_i['SuppliersId'].') file='.basename($_SERVER['PHP_SELF']);
		w_logs(__DIR__."\logs\\", $logs_content);

		
		$_SESSION['last'] = $import_id;
		
		$oDB = Null;

		header('Location:import-history.php');
	}
	
}else{
	header('Location:../404.html');
}

