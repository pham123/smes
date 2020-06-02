<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('config.php');
require('function/sdb.php');
$pagetitle ="Login Page";
require('views/template-header.php');
require('function/template.php');
$oDB = new sdb();

//reset all session variable
session_unset();
echo "hello";

$username = $_POST['username'];
$userpass = md5($_POST['userpass']);

// Kiểm tra trong data base
  //   $where = "`UsersName`='".$username."' AND `UsersPassword` = '".$userpass."'";



if (isset($_POST['username'])) {
  // Lấy giá trị user name và pass
  $username = $_POST['username'];
  $userpass = md5($_POST['userpass']);
  
  // Kiểm tra trong data base
    //   $where = "`UsersName`='".$username."' AND `UsersPassword` = '".$userpass."'";
    $sql = "SELECT * FROM `Users` WHERE `UsersName`=? AND `UsersPassword` = ?";
    $ketqua = $oDB-> query($sql,$username,$userpass)->fetchArray();

  if (isset($ketqua["UsersId"])) {
    $_SESSION[_site_]['userid']=$ketqua['UsersId'];
    $_SESSION[_site_]['username']=$username;
    $_SESSION[_site_]['userfullname']=$ketqua['UsersFullName'];
    $_SESSION[_site_]['useroption']=$ketqua['UsersOption'];
    $_SESSION[_site_]['useremail'] = $ketqua['UsersEmail'];
    $_SESSION[_site_]['userlang'] = $ketqua['UsersLang'];

    setcookie(_site_.'user_id', $ketqua['UsersId'], time() + (86400 * 30), "/");
    setcookie(_site_.'user_name', $username, time() + (86400 * 30), "/");
    setcookie(_site_.'user_fname', $ketqua['UsersFullName'], time() + (86400 * 30), "/");
    setcookie(_site_.'permision', $ketqua['UsersOption'], time() + (86400 * 30), "/");
    setcookie(_site_.'pass', $userpass, time() + (86400 * 30), "/");
 
    header('Location: index.php');

  } else {
    # code...
  }
}


?>