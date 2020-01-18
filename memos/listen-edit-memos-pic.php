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

$field_values = '';

foreach ($_POST as $key => $value) {
	$field_values .= "`".$key."` = '".safe($value)."',";
}
$field_values = trim($field_values,",");

$oDB->update('Memos',$field_values,'`MemosId`='.$id);

// $id = $oDB->sl_id('Memos');

//$products -> getnum($ProductsNumber);

// Phần này xử lý file upload lên

        $target_dir = "image/";
		$target_file = $target_dir . basename($_FILES["MemosPictureAfter"]["name"]);
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
		    if (move_uploaded_file($_FILES["MemosPictureAfter"]["tmp_name"], $target_file)) {
		        echo "The file ". basename( $_FILES["MemosPictureAfter"]["name"]). " has been uploaded.";
		        //Đổi tên
		        // rename($target_file, "image/".$_FILES["issuepicture"]["name"].".jpg");
				rename($target_file, "image/imgafter_".$id.".jpg");
				
		        //thay doi kich thuoc anh
				   $resize = new ResizeImage("image/imgafter_".$id.".jpg");
					$resize->resizeTo(1500, 1500, 'maxWidth');
					$resize->saveImage("image/imgafter_".$id.".jpg");

					$resize = new ResizeImage("image/imgafter_".$id.".jpg");
					$resize->resizeTo(100, 100, 'maxWidth');
					$resize->saveImage("image/small/imgafter_".$id.".jpg");

		        // header('Location: index.php');
		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
        }
		
		
echo "</br>";
        $target_dir = "files/";
		echo $target_file = $target_dir . basename($_FILES["MemosReport"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

		if($imageFileType != "pptx" && $imageFileType != "ppt") {
		    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		    $uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["MemosReport"]["tmp_name"], $target_file)) {
		        echo "The file ". basename( $_FILES["MemosReport"]["name"]). " has been uploaded.";
		        //Đổi tên
		        // rename($target_file, "image/".$_FILES["issuereport"]["name"].".jpg");
				rename($target_file, "files/files_".$id.".".$imageFileType);
				
		        //thay doi kich thuoc anh

		        // header('Location: index.php');
		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
        }
        
        $oDB = Null;
header('Location:EditMemosPic.php?id='.$id);