<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/sdb.php');
require('../function/MysqliDb.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$pagetitle = $user->module;
$oDB = new db();
$sDB = new sdb();
// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";
if (isset($_POST)&&count($_POST)>1) {
  auto_insert('MEInfor',$_POST,$sDB);
}
header("Location:Update.php?id=".$_POST['ProductsId']);