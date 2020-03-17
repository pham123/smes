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

		return 'Empty product id';

	} else if (!ctype_digit($_GET['id'])) {

		$errors[] = 'Invalid product id';
		return;

	} else {
		$id = $_GET['id'];
		$filename = $_FILES['fileToUpload']['name'];
		$tmp = explode(".", $filename);
		$ext = end($tmp);
		$insert_id = $newDB->insert('documentdetail', [
			'DocumentId' => $id,
			'DocumentDetailVersion' => $_POST['DocumentDetailVersion'],
			'DocumentDetailDesc' => $_POST['DocumentDetailDesc'],
			'DocumentDetailFileName' => $filename
		]);
		$target_dir = "files/";
		$target_file = $target_dir . $insert_id.'.'.$ext;

		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

		} else {
			echo "Sorry, there was an error uploading your file.";
		}

		header('Location:documentlistdetail.php');
	}
	
}else{
	header('Location:../404.html');
}

