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

		echo 'Empty export id';
		exit();

	} else if (!ctype_digit($_GET['id'])) {

		echo 'Invalid export id';
		exit();

	} else {
		$export_id = (int)$_GET['id'];
		$newDB->where('ExportsId', $export_id);
		$old_e = $newDB->getOne('exports');
		
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
		
		$oDB ->query($update_sql);
		// echo $update_sql;
		$newDB->where('ExportsId', $export_id);
		$new_e = $newDB->getOne('exports');
		// return;
		$logs_content = 'exports '.$_SESSION[_site_]['username'].' update '.$export_id.' PO('.$old_e['ExportsDocNo'].'=>'.$new_e['ExportsDocNo'].')'.' Date('.$old_e['ExportsDate'].'=>'.$new_e['ExportsDate'].')'.' SectionId('.$old_e['SectionId'].'=>'.$new_e['SectionId'].') file='.basename($_SERVER['PHP_SELF']);
		w_logs(__DIR__."\logs\\", $logs_content);

		
		$_SESSION['last'] = $export_id;
		
		$oDB = Null;

		header('Location:export-history.php');
	}
	
}else{
	header('Location:../404.html');
}

