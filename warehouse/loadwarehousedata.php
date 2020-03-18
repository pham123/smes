<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$newDB->where('ProductsOption', 4, '!=');
$product_arr = $newDB->get('products');

$arr = [];
$arr['products_data'] = $product_arr;

//FIND NOT SUBMIT PURCHASE
$newDB->where('StockOutputsStatus', 0);
$newDB->where('UsersId', $_SESSION[_site_]['userid']);
$n_submit_stockoutput = $newDB->getOne('StockOutputs');

if($n_submit_stockoutput){
    $arr['StockOutputsId'] = $n_submit_stockoutput['StockOutputsId'];
    $arr['FromId'] = $n_submit_stockoutput['FromId'];
    $arr['ToId'] = $n_submit_stockoutput['ToId'];
    $arr['ModelsId'] = $n_submit_stockoutput['ModelsId'];
    $arr['StockOutputsDate'] = $n_submit_stockoutput['StockOutputsDate'];
    $arr['StockOutputsNo'] = $n_submit_stockoutput['StockOutputsNo'];
    $arr['StockOutputsBks'] = $n_submit_stockoutput['StockOutputsBks'];
    $arr['StockOutputsType'] = $n_submit_stockoutput['StockOutputsType'];

    $newDB->where('StockOutputsId', $n_submit_stockoutput['StockOutputsId']);
    $stockoutputitems = $newDB->get('stockoutputitems');
    $arr['stockoutputitems'] = $stockoutputitems;
}else{
    $newDB->where('StockOutputsNo', date('ymd').'-%', 'like');
    $c = count($newDB->get('stockoutputs'));
    $stockoutput_data = [
        'UsersId' => $_SESSION[_site_]['userid'],
        'FromId' => "0",
        'ToId' => "0",
        'ModelsId' => "0",
        'StockOutputsDate' => date('Y-m-d'),
        'StockOutputsType' => '',
        'StockOutputsNo' => 'X'+date('ymd').'-'.($c+1),
        'StockOutputsStatus' => 0,
        'StockOutputsBks' => ''
        
    ];
    $stockoutput_id = $newDB->insert('stockoutputs',$stockoutput_data);

    $newDB->where('StockOutputsId', $stockoutput_id);
    $last_stockoutput = $newDB->getOne('stockoutputs');

    $arr['StockOutputsId'] = $last_stockoutput['StockOutputsId'];
    $arr['FromId'] = $last_stockoutput['FromId'];
    $arr['ToId'] = $last_stockoutput['ToId'];
    $arr['ModelsId'] = $last_stockoutput['ModelsId'];
    $arr['StockOutputsType'] = $last_stockoutput['StockOutputsType'];
    $arr['StockOutputsNo'] = $last_stockoutput['StockOutputsNo'];
    $arr['StockOutputsDate'] = $last_stockoutput['StockOutputsDate'];
    $arr['StockOutputsBks'] = $last_stockoutput['StockOutputsBks'];

    $arr['stockoutputitems'] = [];

}


echo json_encode($arr);
return;
?>