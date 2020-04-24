<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/sdb.php');
require('../function/function.php');
$sDB = new sdb();
// Kiểm tra xem name đã tồn tại hay chưa


if (isset($_POST['DocumentName'])) {
  //Kiểm tra 


  //ok thì tạo bản ghi

  //Lấy về ID bản ghi vừa rồi
  $sql = "INSERT INTO Document (`DocumentName`,`DocumentDescription`,`DocumentTypeId`,`SectionId`,`UsersId`,`DocumentNumber`) VALUES (?,?,?,?,?,?)";

  $sDB -> query($sql,$_POST['DocumentName'],$_POST['DocumentDescription'],$_POST['DocumentTypeId'],$_POST['SectionId'],$_SESSION[_site_]['userid'],$_POST['DocumentNumber']);
  $last = $sDB->lastInsertID();
}
$sDB = Null;
header('location:index.php?'.$last);