<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$arr = array();
// $newDB->where
if(isset($_GET['tracestationid'])){
    $tracestationId = $_GET['tracestationid'];
    
    $newDB->where('TraceStationId', $tracestationId);
    $patterns = $newDB->get('labelpattern', null, 'ProductsId,LabelPatternValue,LabelPatternPackingStandard');
    
    $arr['patterns'] = $patterns;
}

$newDB->where('ProductsOption', 4, '!=');
$products = $newDB->get('products');

$arr['products'] = $products;

echo json_encode($arr);
return;
?>