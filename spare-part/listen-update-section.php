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

		echo 'Empty section id';
		exit();

	} else if (!ctype_digit($_GET['id'])) {

		echo 'Invalid section id';
		exit();

	} else {
		$section_id = (int)$_GET['id'];
		
		$text = '';

		foreach ($_POST as $key => $value) {
			if ($key=='action'||$key=='target'||$key=='SectionId') {
			
			}else{
				if($value){
					$text = $text.$key." = '".$value."',";
				}
			}
		}
		$text = rtrim($text, ',');
		
		$update_sql = "Update Section Set ".$text."
					  Where SectionId = ".$section_id;
		
		// echo $update_sql;

		$oDB ->query($update_sql);
		
		$oDB = Null;

		header('Location:section_index.php');
	}
	
}else{
	header('Location:../404.html');
}


