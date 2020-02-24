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
$id = safe($_GET['id']);

if ($user->acess()==1) {
	$oDB->delete('Memos','MemosId='.$id);
}
header('Location:Memoslist.php');