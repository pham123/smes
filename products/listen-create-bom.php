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
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	$data = array_filter($_POST);
	
	$bomid = $newDB->insert('Boms', $data);

	//update BOM path
	if($bomid){
		$newDB->where("BomsId", $_POST['BomsParentId']);
		$parent_bom = $newDB->getOne("Boms");
		
		$newDB->where('BomsId', $bomid);
		$newDB->update('Boms', ['BomsPath' => $parent_bom['BomsPath'].'-'.$bomid]);
	}

	$newDB = null;

	header('Location:viewbom.php?id='.$_POST['BomlistsId']);
	
}else{
	header('Location:../404.html');
}