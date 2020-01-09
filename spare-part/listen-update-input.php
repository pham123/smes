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

		echo 'Empty input id';
		exit();

	} else if (!ctype_digit($_GET['id'])) {

		echo 'Invalid input id';
		exit();

	} else {
		$input_id = (int)$_GET['id'];
		
		$text = '';

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
		
		$oDB = Null;

		header('Location:input-list.php');
	}
	
}else{
	header('Location:../404.html');
}

