<?php
//var_dump($_FILES);

$postid = 1;
$namepre = '191008';

$target_dir = "files/";
$valid_formats = array("JPG","jpg","png","PNG");
$max_file_size = 1024*1024*20; //20 mb
$path = "../images/"; // Upload directory
$count = 0;
$filesar = array();
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
	// Loop $_FILES to exeicute all files
 
        $name = $_FILES['file']['name'];
	    // if ($_FILES['file']['error'] == 4) {
	    //     //continue; // Skip file if any error found
	    // }	       
	    if ($_FILES['file']['error'] == 0) {	           
	        if ($_FILES['file']['size'] > $max_file_size) {
	            $message[] = "$name is too large!.";
	            //continue; // Skip large files
	        }
			elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
                $message[] = "$name is not a valid format";
                
				//continue; // Skip invalid file formats
			}
			else{ // No error found! Move uploaded files 
				//$name = iconv("utf-8", "cp936", $name);
	            if(move_uploaded_file($_FILES["file"]["tmp_name"], $path.$name))
				$count++; // Number of successfully uploaded file
				$dateoffile = $namepre;
				//Lấy ra file name
				$fileinfor['type'] = pathinfo($name, PATHINFO_EXTENSION);
				//Đổi lại tên của file
				rename($path.$name, "files/".$dateoffile.'_'.$postid.'_'.$count.'.'.$fileinfor['type']);
				//Truyền file name đã được đổi vào biến
				$fileinfor['name'] = $dateoffile.'_'.$postid.'_'.$count.'.'.$fileinfor['type'];
				//Lấy lại tên nguyên gốc của file
				$fileinfor['dpname'] = $name;
                $fileinfor['type'] = pathinfo($name, PATHINFO_EXTENSION);
                array_push($filesar,$fileinfor);
	        }
	    }

}


var_dump($fileinfor);
