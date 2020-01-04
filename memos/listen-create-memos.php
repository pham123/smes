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
// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";



// $PartsId = safe($_POST['PartsId']);
// $AreasId = safe($_POST['AreasId']);
// $MemoReduceId = safe($_POST['MemoReduceId']);
// $MemosLocation = safe($_POST['MemosLocation']);
// $MemosIssue = safe($_POST['MemosIssue']);
// $MemosContent = safe($_POST['MemosContent']);
// $MemosEfficiency = safe($_POST['MemosEfficiency']);
// $UserId = safe($_POST['UserId']);
// $MemosCreator = safe($_POST['MemosCreator']);
// $MemosPic = safe($_POST['MemosPic']);
// $MemosApplyDate = safe($_POST['MemosApplyDate']);
// $MemosOption = safe($_POST['MemosOption']);


$field_values = '';

foreach ($_POST as $key => $value) {
	$field_values .= "`".$key."` = '".safe($value)."',";
}
$field_values = trim($field_values,",");
// echo $field_values;
// echo "</br>";
// $a = $oDB->getcol('Memos');
// // var_dump($a);
// echo implode(', ',$a);
// exit();
//Rồi update
// $field_values = "`PartsId` = '".$PartsId."',
// `AreasId` = '".$AreasId."',
// `QualityIssuelistDate` = '".$QualityIssuelistDate."',
// `SupplyChainObjectId` = '".$SupplyChainObjectId."',
// `ProductsId` = '".$ProductsId."',
// `QualityIssuelistCreator` = '".$QualityIssuelistCreator."',
// `QualityIssuelistLotNo` = '".$QualityIssuelistLotNo."',
// `QualityIssuelistProductionDate` = '".$QualityIssuelistProductionDate."',
// `QualityIssuelistLotQuantity` = '".$QualityIssuelistLotQuantity."',
// `QualityIssuelistNgQuantity` = '".$QualityIssuelistNgQuantity."',
// `QualityIssuelistTimesOccurs` = '".$QualityIssuelistTimesOccurs."',
// `QualityIssuelistDocNo` = '".$QualityIssuelistDocNo."',
// `QualityIssuelistDueDate` = '".$QualityIssuelistDueDate."',
// `QualityIssuelistFinishDate` = '".$QualityIssuelistFinishDate."',
// `QualityIssuelistRootCause` = '".$QualityIssuelistRootCause."',
// `QualityIssuelistAction` = '".$QualityIssuelistAction."',
// `QualityIssuelistStatus` = '".$QualityIssuelistStatus."',
// `UsersId` = '".$UsersId."'
// ";

$oDB->insert('Memos',$field_values);

$id = $oDB->sl_id('Memos');

//$products -> getnum($ProductsNumber);

// Phần này xử lý file upload lên

        $target_dir = "image/";
		$target_file = $target_dir . basename($_FILES["MemosPicture"]["name"]);
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
		    if (move_uploaded_file($_FILES["MemosPicture"]["tmp_name"], $target_file)) {
		        echo "The file ". basename( $_FILES["MemosPicture"]["name"]). " has been uploaded.";
		        //Đổi tên
		        // rename($target_file, "image/".$_FILES["issuepicture"]["name"].".jpg");
				rename($target_file, "image/img_".$id.".jpg");
				
		        //thay doi kich thuoc anh
				   $resize = new ResizeImage("image/img_".$id.".jpg");
					$resize->resizeTo(1500, 1500, 'maxWidth');
					$resize->saveImage("image/img_".$id.".jpg");

					$resize = new ResizeImage("image/img_".$id.".jpg");
					$resize->resizeTo(100, 100, 'maxWidth');
					$resize->saveImage("image/small/img_".$id.".jpg");

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
header('Location:memoslist.php');