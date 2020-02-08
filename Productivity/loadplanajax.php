<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

$shifts = $newDB->get('shift',null,'ShiftId,ShiftName');

function baseItem($shifts){
    $item = [];
    foreach($shifts as $sh){
        $item['shift_'.$sh['ShiftId']] = 0;
    }
    return $item;
}

// $newDB->where
$tracestationId = $_GET['tracestationid'];
$date = $_GET['date'];

$newDB->where('TraceStationId', $tracestationId);
$newDB->where('ProPlanDate', $date);
$products = $newDB->get('proplan', null, 'distinct ProductsId');
// echo '<pre>';
// print_r($plans);
// echo '</pre>';
// return;
$proplans = [];
foreach($products as $p){
    $item = baseItem($shifts);
    $item['ProductsId'] = $p['ProductsId'];
    $newDB->where('TraceStationId', $tracestationId);
    $newDB->where('ProPlanDate', $date);
    $newDB->where('ProductsId', $p['ProductsId']);
    $plans = $newDB->get('proplan');
    foreach($plans as $pl){
        $item['shift_'.$pl['ShiftId']] = $pl['ProPlanQuantity'];
    }
    $proplans[]=$item;
}

$arr['plans'] = $proplans;

echo json_encode($arr);
return;
?>