<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

require '../vendor/autoload.php';
require('../config.php');
require('../function/sdb.php');
require('../function/function.php');
$sDB = new sdb();

var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//CHECK ID IS VALID
	if (!isset($_POST['DocumentId'])) {

		return 'Empty product id';

	} else if (!ctype_digit($_POST['DocumentId'])) {

		return 'Empty product id';

	} else {
		$id = $_POST['DocumentId'];
		$mother = $_POST['mother'];

		$sql = "INSERT INTO `smes`.`documentap` (`ProductsId`, `RelatedDocumentId`) VALUES (?, ?)";

		$sDB->query($sql,$mother,$id);
		header('Location:assignproduct.php?ProductsId='.$mother);
	}
	
}else{
	header('Location:../404.html');
}

