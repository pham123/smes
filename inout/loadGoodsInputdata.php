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
$newDB->where('GoodsInputsStatus', 0);
$newDB->where('UsersId', $_SESSION[_site_]['userid']);
$n_submit_GoodsInput = $newDB->getOne('GoodsInputs');

if($n_submit_GoodsInput){
    $arr['GoodsInputsId'] = $n_submit_GoodsInput['GoodsInputsId'];
    $arr['FromId'] = $n_submit_GoodsInput['FromId'];
    $arr['ToId'] = $n_submit_GoodsInput['ToId'];
    $arr['GoodsInputsDate'] = $n_submit_GoodsInput['GoodsInputsDate'];
    $arr['GoodsInputsNo'] = $n_submit_GoodsInput['GoodsInputsNo'];
    $arr['GoodsInputsBks'] = $n_submit_GoodsInput['GoodsInputsBks'];
    $arr['GoodsInputsType'] = $n_submit_GoodsInput['GoodsInputsType'];

    $newDB->where('GoodsInputsId', $n_submit_GoodsInput['GoodsInputsId']);
    $GoodsInputitems = $newDB->get('GoodsInputitems');
    $arr['GoodsInputitems'] = $GoodsInputitems;
}else{
    $newDB->where('GoodsInputsNo', date('ymd').'-%', 'like');
    $c = count($newDB->get('GoodsInputs'));
    $GoodsInput_data = [
        'UsersId' => $_SESSION[_site_]['userid'],
        'FromId' => "0",
        'ToId' => "0",
        'GoodsInputsDate' => date('Y-m-d'),
        'GoodsInputsType' => '',
        'GoodsInputsNo' => 'N'.date('ymd').'-'.($c+1),
        'GoodsInputsStatus' => 0,
        'GoodsInputsBks' => ''
        
    ];
    $GoodsInput_id = $newDB->insert('GoodsInputs',$GoodsInput_data);

    $newDB->where('GoodsInputsId', $GoodsInput_id);
    $last_GoodsInput = $newDB->getOne('GoodsInputs');

    $arr['GoodsInputsId'] = $last_GoodsInput['GoodsInputsId'];
    $arr['FromId'] = $last_GoodsInput['FromId'];
    $arr['ToId'] = $last_GoodsInput['ToId'];
    $arr['GoodsInputsType'] = $last_GoodsInput['GoodsInputsType'];
    $arr['GoodsInputsNo'] = $last_GoodsInput['GoodsInputsNo'];
    $arr['GoodsInputsDate'] = $last_GoodsInput['GoodsInputsDate'];
    $arr['GoodsInputsBks'] = $last_GoodsInput['GoodsInputsBks'];

    $arr['GoodsInputitems'] = [];

}


echo json_encode($arr);
return;
?>