<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/sdb.php');
require('../function/function.php');
$sDB = new sdb();

if (isset($_POST['DocumentTypeId'])) {
  $sql = "UPDATE `documenttype` SET `DocumentTypeName`=?,`DocumentTypeDescription`=?,`DocumentTypeCode`=?,`DocumentTypeNameVi`=? WHERE DocumentTypeId=?";

  $sDB -> query($sql,$_POST['DocumentTypeName'],$_POST['DocumentTypeDescription'],$_POST['DocumentTypeCode'],$_POST['DocumentTypeNameVi'],$_POST['DocumentTypeId']);
}
$sDB = Null;
header('location:documenttype.php');