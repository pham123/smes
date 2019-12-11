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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//CHECK ID IS VALID
	if (!isset($_GET['id'])) {

		echo 'Empty product id';
		exit();

	} else if (!ctype_digit($_GET['id'])) {

		echo 'Invalid product id';
		exit();

	} else {
		$product_id = (int)$_GET['id'];
		
		$text = '';

		foreach ($_POST as $key => $value) {
			if ($key=='action'||$key=='target'||$key=='ProductsId') {
			
			}else{
				if($value){
					$text = $text.$key." = '".$value."',";
				}
			}
		}
		$text = rtrim($text, ',');
		
		$update_sql = "Update products Set ".$text."
					  Where ProductsId = ".$product_id;
		
		// echo $update_sql;

		$oDB ->query($update_sql);

		if(isset($_FILES["fileToUpload"]["name"])){
			uploadProductPicture($product_id);
		}
		
		$_SESSION['last'] = $product_id;
		
		$oDB = Null;
		$products = Null;

		header('Location:index.php');
	}
	
}else{
	header('Location:../404.html');
}

// Phần này xử lý file upload lên
function uploadProductPicture($product_id) {
			
	$target_dir = "image/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	//var_dump($_POST);
	//echo "<br>";
	//var_dump($_FILES);
	//exit();
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
			rename($target_file, "image/img_".$product_id.".jpg");
			
			//thay doi kich thuoc anh
				$resize = new ResizeImage("image/img_".$product_id.".jpg");
				$resize->resizeTo(1500, 1500, 'maxWidth');
				$resize->saveImage("image/img_".$product_id.".jpg");

				$resize = new ResizeImage("image/img_".$product_id.".jpg");
				$resize->resizeTo(100, 100, 'maxWidth');
				$resize->saveImage("image/small/img_".$product_id.".jpg");

			return true;

		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
	return false;
}

