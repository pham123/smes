<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$newDB->where('MaterialTypesId', $_GET['mtpid']);
$product_arr = $newDB->get('products');

$arr = [];
$arr['products_data'] = $product_arr;

//FIND NOT SUBMIT PURCHASE
$newDB->where('StockOutputsStatus', 0);
$newDB->where('UsersId', $_SESSION[_site_]['userid']);
$newDB->where('StockOutputsModule', 'inout');
$n_submit_StockOutput = $newDB->getOne('StockOutputs');

if($n_submit_StockOutput){
    $arr['StockOutputsId'] = $n_submit_StockOutput['StockOutputsId'];
    $arr['FromId'] = $n_submit_StockOutput['FromId'];
    $arr['ToId'] = $n_submit_StockOutput['ToId'];
    $arr['StockOutputsDate'] = $n_submit_StockOutput['StockOutputsDate'];
    $arr['StockOutputsNo'] = $n_submit_StockOutput['StockOutputsNo'];
    $arr['StockOutputsBks'] = $n_submit_StockOutput['StockOutputsBks'];
    $arr['StockOutputsType'] = $n_submit_StockOutput['StockOutputsType'];

    $newDB->where('StockOutputsId', $n_submit_StockOutput['StockOutputsId']);
    $StockOutputitems = $newDB->get('StockOutputitems');
    $arr['StockOutputitems'] = $StockOutputitems;
}else{
    $newDB->where('StockOutputsNo', date('ymd').'-%', 'like');
    $c = count($newDB->get('StockOutputs'));
    $StockOutput_data = [
        'UsersId' => $_SESSION[_site_]['userid'],
        'FromId' => "0",
        'ToId' => "0",
        'StockOutputsDate' => date('Y-m-d'),
        'StockOutputsType' => '',
        'StockOutputsNo' => 'X'.date('ymd').'-'.($c+1),
        'StockOutputsStatus' => 0,
        'StockOutputsBks' => '',
        'StockOutputsModule' => 'inout'
        
    ];
    $StockOutput_id = $newDB->insert('StockOutputs',$StockOutput_data);

    $newDB->where('StockOutputsId', $StockOutput_id);
    $last_StockOutput = $newDB->getOne('StockOutputs');

    $arr['StockOutputsId'] = $last_StockOutput['StockOutputsId'];
    $arr['FromId'] = $last_StockOutput['FromId'];
    $arr['ToId'] = $last_StockOutput['ToId'];
    $arr['StockOutputsType'] = $last_StockOutput['StockOutputsType'];
    $arr['StockOutputsNo'] = $last_StockOutput['StockOutputsNo'];
    $arr['StockOutputsDate'] = $last_StockOutput['StockOutputsDate'];
    $arr['StockOutputsBks'] = $last_StockOutput['StockOutputsBks'];

    $arr['StockOutputitems'] = [];

}


echo json_encode($arr);
return;
?>