<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/function.php');

$oDB = new db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION[_site_]['userid'];
    
    $text = '';

    foreach ($_POST as $key => $value) {
		if($key == 'UsersPicture')
		{
			continue;
		}
        if($key != 'UsersPassword' && !empty(trim($value)))
        {
            $text = $text.$key." = '".$value."',";
		}else if($key == 'UsersPassword' && !empty(trim($value)))
		{
			$text = $text.$key." = '".md5(trim($value))."',";
		}
    }
	$text = rtrim($text, ',');
    
    $update_sql = "Update users Set ".$text."
                    Where UsersId = ".$_SESSION[_site_]['userid'];
    
    // echo $update_sql;

	$oDB ->query($update_sql);
	
	// update session variable
	$_SESSION[_site_]['userfullname'] = $_POST['UsersFullName'];
	$_SESSION[_site_]['useremail'] = $_POST['UsersEmail'];

    if(isset($_FILES["UsersPicture"]["name"])){
		uploadUserPicture($_SESSION[_site_]['userid']);
    }
    
    $oDB = Null;
    $products = Null;

    header('Location:../home/index.php');
	
}else{
	header('Location:../404.html');
}

// Phần này xử lý file upload lên
function uploadUserPicture($user_id) {
	$target_dir = "image/";
	$target_file = $target_dir . basename($_FILES["UsersPicture"]["name"]);
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
		if (move_uploaded_file($_FILES["UsersPicture"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["UsersPicture"]["name"]). " has been uploaded.";
			//Đổi tên
			// rename($target_file, "image/".$_FILES["fileToUpload"]["name"].".jpg");
			rename($target_file, "image/user_".$user_id.".jpg");
			
			// //thay doi kich thuoc anh
				$resize = new ResizeImage("image/user_".$user_id.".jpg");
				$resize->resizeTo(250, 250, 'maxWidth');
				$resize->saveImage("image/user_".$user_id.".jpg");

			// 	$resize = new ResizeImage("image/img_".$user_id.".jpg");
			// 	$resize->resizeTo(100, 100, 'maxWidth');
			// 	$resize->saveImage("image/small/img_".$user_id.".jpg");

			return true;

		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
	return false;
}

