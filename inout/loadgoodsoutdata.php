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
$newDB->where('GoodsOutputsStatus', 0);
$newDB->where('UsersId', $_SESSION[_site_]['userid']);
$n_submit_GoodsOutput = $newDB->getOne('GoodsOutputs');

if($n_submit_GoodsOutput){
    $arr['GoodsOutputsId'] = $n_submit_GoodsOutput['GoodsOutputsId'];
    $arr['FromId'] = $n_submit_GoodsOutput['FromId'];
    $arr['ToId'] = $n_submit_GoodsOutput['ToId'];
    $arr['GoodsOutputsDate'] = $n_submit_GoodsOutput['GoodsOutputsDate'];
    $arr['GoodsOutputsNo'] = $n_submit_GoodsOutput['GoodsOutputsNo'];
    $arr['GoodsOutputsBks'] = $n_submit_GoodsOutput['GoodsOutputsBks'];
    $arr['GoodsOutputsType'] = $n_submit_GoodsOutput['GoodsOutputsType'];

    $newDB->where('GoodsOutputsId', $n_submit_GoodsOutput['GoodsOutputsId']);
    $GoodsOutputitems = $newDB->get('GoodsOutputitems');
    $arr['GoodsOutputitems'] = $GoodsOutputitems;
}else{
    $newDB->where('GoodsOutputsNo', date('ymd').'-%', 'like');
    $c = count($newDB->get('GoodsOutputs'));
    $GoodsOutput_data = [
        'UsersId' => $_SESSION[_site_]['userid'],
        'FromId' => "0",
        'ToId' => "0",
        'GoodsOutputsDate' => date('Y-m-d'),
        'GoodsOutputsType' => '',
        'GoodsOutputsNo' => 'X'.date('ymd').'-'.($c+1),
        'GoodsOutputsStatus' => 0,
        'GoodsOutputsBks' => ''
        
    ];
    $GoodsOutput_id = $newDB->insert('GoodsOutputs',$GoodsOutput_data);

    $newDB->where('GoodsOutputsId', $GoodsOutput_id);
    $last_GoodsOutput = $newDB->getOne('GoodsOutputs');

    $arr['GoodsOutputsId'] = $last_GoodsOutput['GoodsOutputsId'];
    $arr['FromId'] = $last_GoodsOutput['FromId'];
    $arr['ToId'] = $last_GoodsOutput['ToId'];
    $arr['GoodsOutputsType'] = $last_GoodsOutput['GoodsOutputsType'];
    $arr['GoodsOutputsNo'] = $last_GoodsOutput['GoodsOutputsNo'];
    $arr['GoodsOutputsDate'] = $last_GoodsOutput['GoodsOutputsDate'];
    $arr['GoodsOutputsBks'] = $last_GoodsOutput['GoodsOutputsBks'];

    $arr['GoodsOutputitems'] = [];

}


echo json_encode($arr);
return;
?>