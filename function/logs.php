<?php
function dblogs($content){
	$name = date("Ymd");
	$content = preg_replace('~[\r\n]+~', ' ', $content);
	$text = $content.';'.PHP_EOL;
	if (!file_exists ("db".$name.".txt")) {
		$myfile = fopen("db".$name.".txt", "w") or die("Unable to open file!");
		file_put_contents ("db".$name.".txt",$text ,FILE_APPEND);
	}else{
		file_put_contents ("db".$name.".txt",$text ,FILE_APPEND);
	}
}