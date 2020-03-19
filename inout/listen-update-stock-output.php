<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$data = $_POST;

foreach ($data['StockOutputItemsId'] as $index => $id) {
    $newDB->where('StockOutputItemsId', $id);
    $item = $newDB->getOne('stockoutputitems');
    if($item['StockOutputItemsCartQty'] != $data['StockOutputItemsCartQty'][$index] || $item['StockOutputItemsQty'] != $data['StockOutputItemsQty'][$index] || $item['StockOutputItemsRemark'] != $data['StockOutputItemsRemark'][$index]){
        //update stock output item
        $newDB->where('StockOutputItemsId', $id);
        $newDB->update('stockoutputitems', [
            'StockOutputItemsCartQty' => $data['StockOutputItemsCartQty'][$index],
            'StockOutputItemsQty' => $data['StockOutputItemsQty'][$index],
            'StockOutputItemsRemark' => $data['StockOutputItemsRemark'][$index]
        ]);
        //log
        $data_log = [
            'StockOutputItemsId' => $id
        ];
        if($item['StockOutputItemsCartQty'] != $data['StockOutputItemsCartQty'][$index]){
            $data_log['StockOutputItemsCartQty'] = $data['StockOutputItemsCartQty'][$index];
        }
        if($item['StockOutputItemsQty'] != $data['StockOutputItemsQty'][$index]){
            $data_log['StockOutputItemsQty'] = $data['StockOutputItemsQty'][$index];
        }
        if($item['StockOutputItemsRemark'] != $data['StockOutputItemsRemark'][$index]){
            $data_log['StockOutputItemsRemark'] = $data['StockOutputItemsRemark'][$index];
        }
        $data_log['UsersId'] = $_SESSION[_site_]['userid'];
        $newDB->insert('stockoutputitemlogs',$data_log);
    }
}
header('Location: stockout.php');
return;

?>