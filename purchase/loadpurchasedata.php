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
$newDB->where('PurchasesStatus', 0);
$newDB->where('UsersId', $_SESSION[_site_]['userid']);
$n_submit_purchase = $newDB->getOne('Purchases');

if($n_submit_purchase){
    $arr['PurchasesId'] = $n_submit_purchase['PurchasesId'];
    $arr['RequestSectionId'] = $n_submit_purchase['RequestSectionId'];
    $arr['ReceiveSectionId'] = $n_submit_purchase['ReceiveSectionId'];
    $arr['TraceStationId'] = $n_submit_purchase['TraceStationId'];
    $arr['IsUrgent'] = $n_submit_purchase['IsUrgent'];
    $arr['PurchasesDate'] = $n_submit_purchase['PurchasesDate'];
    $arr['PurchasesNo'] = $n_submit_purchase['PurchasesNo'];
    $arr['PurchasesComment'] = $n_submit_purchase['PurchasesComment'];

    $newDB->where('PurchasesId', $n_submit_purchase['PurchasesId']);
    $purchaseitems = $newDB->get('purchaseitems');
    $arr['purchaseitems'] = $purchaseitems;
}else{
    $purchases_data = [
        'UsersId' => $_SESSION[_site_]['userid'],
        'RequestSectionId' => null,
        'ReceiveSectionId' => null,
        'TraceStationId' => null,
        'IsUrgent' => 0,
        'PurchasesDate' => null,
        'PurchasesNo' => '',
        'PurchasesComment' => '',
        'PurchasesStatus' => 0
    ];
    $purchase_id = $newDB->insert('Purchases',$purchases_data);

    $newDB->where('PurchasesId', $purchase_id);
    $last_purchase = $newDB->getOne('Purchases');

    $arr['PurchasesId'] = $last_purchase['PurchasesId'];
    $arr['RequestSectionId'] = $last_purchase['RequestSectionId'];
    $arr['ReceiveSectionId'] = $last_purchase['ReceiveSectionId'];
    $arr['TraceStationId'] = $last_purchase['TraceStationId'];
    $arr['IsUrgent'] = $last_purchase['IsUrgent'];
    $arr['PurchasesDate'] = $last_purchase['PurchasesDate'];
    $arr['PurchasesNo'] = $last_purchase['PurchasesNo'];
    $arr['PurchasesComment'] = $last_purchase['PurchasesComment'];

    $arr['purchaseitems'] = [];

}


echo json_encode($arr);
return;
?>