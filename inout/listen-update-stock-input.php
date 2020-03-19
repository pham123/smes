<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$data = $_POST;

foreach ($data['StockInputItemsId'] as $index => $id) {
    $newDB->where('StockInputItemsId', $id);
    $item = $newDB->getOne('StockInputitems');
    if($item['StockInputItemsCartQty'] != $data['StockInputItemsCartQty'][$index] || $item['StockInputItemsQty'] != $data['StockInputItemsQty'][$index] || $item['StockInputItemsRemark'] != $data['StockInputItemsRemark'][$index]){
        //update stock output item
        $newDB->where('StockInputItemsId', $id);
        $newDB->update('StockInputitems', [
            'StockInputItemsCartQty' => $data['StockInputItemsCartQty'][$index],
            'StockInputItemsQty' => $data['StockInputItemsQty'][$index],
            'StockInputItemsRemark' => $data['StockInputItemsRemark'][$index]
        ]);
        //log
        $data_log = [
            'StockInputItemsId' => $id
        ];
        if($item['StockInputItemsCartQty'] != $data['StockInputItemsCartQty'][$index]){
            $data_log['StockInputItemsCartQty'] = $data['StockInputItemsCartQty'][$index];
        }
        if($item['StockInputItemsQty'] != $data['StockInputItemsQty'][$index]){
            $data_log['StockInputItemsQty'] = $data['StockInputItemsQty'][$index];
        }
        if($item['StockInputItemsRemark'] != $data['StockInputItemsRemark'][$index]){
            $data_log['StockInputItemsRemark'] = $data['StockInputItemsRemark'][$index];
        }
        $data_log['UsersId'] = $_SESSION[_site_]['userid'];
        $newDB->insert('StockInputitemlogs',$data_log);
    }
}
header('Location: stockin.php');
return;

?>