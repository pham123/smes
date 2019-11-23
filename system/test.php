<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../lang/en.php');
$page = 'system';
$pagetitle = _System_;
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <P>
<?php
$target = 'Accesslist';
$sql = "Select TOP 1 *
from ".$target." 
Order by ".$target."Id DESC
";
$ketqua = $oDB-> fetchOne($sql);
$headerar = (array_keys($ketqua));
$text1 = '';
$text2 = '';
foreach ($headerar as $key => $value) {
    if (strpos( $value, $target ) !== false) {
        $text2=$text2.'
        '.$target.'.'.$value.',';
      }else{
        $SelectTable = str_replace('Id', '', $value);
        $text1= $text1.'
        Inner join '.$SelectTable.' on '.$target.'.'.$SelectTable.'Id = '.$SelectTable.'.'.$SelectTable.'Id' ;
        $text2=$text2.'
        '.$SelectTable.'.'.$SelectTable.'Name,';
        
      }
}
$text =rtrim($text2, ',');
$sql = "Select 
".$text2."
from ".$target." 
".$text1."
";


?>
</P>
</body>
</html>
