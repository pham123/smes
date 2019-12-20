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
// $oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = array_filter($_POST);
	$blid = $newDB->insert('Bomlists', $data);
	if($blid){
		$data['BomsParentId'] = 0;
		$data['BomlistsId'] = $blid;
		$data['BomsPath'] = 'S';
		if(isset($data['BomlistsInfo'])){
			unset($data['BomlistsInfo']);
		}
		$bid = $newDB->insert('Boms', $data);
		if($bid){
			$newDB->where('BomsId', $bid);
			$newDB->update('boms', ['BomsPath' => $bid]);
		}
	}

	header('Location:bom_index.php');
	
}else{
	header('Location:../404.html');
}