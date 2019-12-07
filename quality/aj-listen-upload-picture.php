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
$pagetitle = $user->module;
$oDB = new db();

$id = $_SESSION[_site_]['editissueid'];

$target_dir = "image/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
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
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
		$column = "picture";
		$value = $_FILES["file"]["name"];
		$content = $pagetitle."\t".$user->name."\tUpdate\t".$column."\t".$value."\t".$id;
		w_logs('../logs/',$content);
		rename($target_file, "image/img_".$id.".jpg");
		
		//thay doi kich thuoc anh
		   $resize = new ResizeImage("image/img_".$id.".jpg");
			$resize->resizeTo(1024, 1024, 'maxWidth');
			$resize->saveImage("image/img_".$id.".jpg");

			$resize = new ResizeImage("image/img_".$id.".jpg");
			$resize->resizeTo(200, 200, 'maxWidth');
			$resize->saveImage("image/small/img_".$id.".jpg");

		header('Location: index.php');
	} else {
		echo "Sorry, there was an error uploading your file.";
	}
}