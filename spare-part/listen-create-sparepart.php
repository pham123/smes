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

// Ghi thông tin vào database
$post_data = array_filter($_POST);
// echo $post_data['ProductsNumber'];
// echo '<br>';
// echo $post_data['ProductsName'];

$product_id = $newDB->insert('Products', $post_data);

if(!$product_id)
{
	echo 'Error when add new spare part';
	return;
}

$logs_content = 'products '.$_SESSION[_site_]['username'].' create ProductsNumber='.$post_data['ProductsNumber'].' ProductsName='.$post_data['ProductsName'].' Category='.$post_data['ProductsCategory'].' file='.basename($_SERVER['PHP_SELF']);
w_logs(__DIR__."\logs\\", $logs_content);

// Phần này xử lý file upload lên

$target_dir = "image/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
	echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	$uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
	echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		//Đổi tên
		// rename($target_file, "image/".$_FILES["fileToUpload"]["name"].".jpg");
		rename($target_file, "image/".$_POST['ProductsNumber'].".jpg");
		
		//thay doi kich thuoc anh
			$resize = new ResizeImage("image/".$_POST['ProductsNumber'].".jpg");
			$resize->resizeTo(1500, 1500, 'maxWidth');
			$resize->saveImage("image/".$_POST['ProductsNumber'].".jpg");

			$resize = new ResizeImage("image/".$_POST['ProductsNumber'].".jpg");
			$resize->resizeTo(100, 100, 'maxWidth');
			$resize->saveImage("image/small/".$_POST['ProductsNumber'].".jpg");

		// //Upload thông tin về location.
		// $where  = "`sp_code`='".$_POST['code']."'";
		// //$field_values = "`cur_qty`=".$qty;
		// $oDB->update('sp_curent',"`sp_loca`='".$_POST['location']."'",$where);

		$_SESSION['last'] = $product_id;

		header('Location: index.php');
	} else {
		echo "Sorry, there was an error uploading your file.";
	}
}

$oDB = Null;
$products = Null;
header('Location:index.php');