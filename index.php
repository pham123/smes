<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Kiểm tra xem đã có cache chưa
//Nếu có thì lấy thông tin đăng nhập rồi đăng nhập luôn
require('config.php');
require_once('function/sdb.php');
// header("Location: login.php");
// include('function/db_lib.php');

$oDB = new sdb();

// var_dump($_COOKIE);
// $sql = "SELECT * FROM `Users` WHERE `UsersName`='".$_COOKIE[_site_.'user_name']."' AND `UsersPassword` = '".$_COOKIE[_site_.'pass']."'";
$sql = "SELECT * FROM `Users` WHERE `UsersName`=? AND `UsersPassword` = ?";
$ketqua = $oDB-> query($sql,$_COOKIE[_site_.'user_name'],$_COOKIE[_site_.'pass'])->fetchArray();
// $ketqua = $oDB -> in_table("Users",$where);
// var_dump($ketqua);
// exit();
if (isset($ketqua['UsersId'])) {
  $_SESSION[_site_]['userid']=$ketqua['UsersId'];
  $_SESSION[_site_]['username']=$username;
  $_SESSION[_site_]['userfullname']=$ketqua['UsersFullName'];
  $_SESSION[_site_]['useroption']=$ketqua['UsersOption'];
  $_SESSION[_site_]['useremail'] = $ketqua['UsersEmail'];
  $_SESSION[_site_]['userlang'] = $ketqua['UsersLang'];
  header('Location: home/');
	
}else{
  header('Location: login.php');
}