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
    
    $newDB->where('TraceStationId', intval($tracestationId));
    $patterns = $newDB->get('labelpattern', null, 'LabelPatternId,ProductsId,LabelPatternValue,LabelPatternPackingStandard');
    
    $arr['patterns'] = $patterns;

    $newDB->where('TraceStationId', $tracestationId);
    $assigns = $newDB->get('assignmachines', null, 'AssignMachinesId,MachinesId,AssignMachinesDescription');

    $arr['assigns'] = $assigns;
}

$newDB->where('ProductsOption', 4, '!=');
$products = $newDB->get('products');

$machines = $newDB->get('machines');

$arr['products'] = $products;
$arr['machines'] = $machines;

echo json_encode($arr);
return;
?>