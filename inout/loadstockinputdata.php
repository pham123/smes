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

//FIND NOT SUBMIT INPUT
$newDB->where('StockInputsStatus', 0);
$newDB->where('UsersId', $_SESSION[_site_]['userid']);
$newDB->where('StockInputsModule', 'inout');
$n_submit_StockInput = $newDB->getOne('StockInputs');

if($n_submit_StockInput){
    $arr['StockInputsId'] = $n_submit_StockInput['StockInputsId'];
    $arr['FromId'] = $n_submit_StockInput['FromId'];
    $arr['ToId'] = $n_submit_StockInput['ToId'];
    $arr['StockInputsDate'] = $n_submit_StockInput['StockInputsDate'];
    $arr['StockInputsNo'] = $n_submit_StockInput['StockInputsNo'];
    $arr['StockInputsBks'] = $n_submit_StockInput['StockInputsBks'];
    $arr['StockInputsType'] = $n_submit_StockInput['StockInputsType'];

    $newDB->where('StockInputsId', $n_submit_StockInput['StockInputsId']);
    $StockInputitems = $newDB->get('StockInputitems');
    $arr['StockInputitems'] = $StockInputitems;
}else{
    $newDB->where('StockInputsNo', date('ymd').'-%', 'like');
    $c = count($newDB->get('StockInputs'));
    $StockInput_data = [
        'UsersId' => $_SESSION[_site_]['userid'],
        'FromId' => "0",
        'ToId' => "0",
        'StockInputsDate' => date('Y-m-d'),
        'StockInputsType' => '',
        'StockInputsNo' => 'N'.date('ymd').'-'.($c+1),
        'StockInputsStatus' => 0,
        'StockInputsBks' => '',
        'StockInputsModule' => 'inout'
        
    ];
    $StockInput_id = $newDB->insert('StockInputs',$StockInput_data);

    $newDB->where('StockInputsId', $StockInput_id);
    $last_StockInput = $newDB->getOne('StockInputs');

    $arr['StockInputsId'] = $last_StockInput['StockInputsId'];
    $arr['FromId'] = $last_StockInput['FromId'];
    $arr['ToId'] = $last_StockInput['ToId'];
    $arr['StockInputsType'] = $last_StockInput['StockInputsType'];
    $arr['StockInputsNo'] = $last_StockInput['StockInputsNo'];
    $arr['StockInputsDate'] = $last_StockInput['StockInputsDate'];
    $arr['StockInputsBks'] = $last_StockInput['StockInputsBks'];

    $arr['StockInputitems'] = [];

}


echo json_encode($arr);
return;
?>