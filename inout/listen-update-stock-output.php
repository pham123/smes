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
    $item = $newDB->getOne('StockOutputitems');
    if($item['StockOutputItemsUnitPrice'] != $data['StockOutputItemsUnitPrice'][$index] || $item['StockOutputItemsQty'] != $data['StockOutputItemsQty'][$index] || $item['StockOutputItemsRemark'] != $data['StockOutputItemsRemark'][$index]){
        //update stock output item
        $newDB->where('StockOutputItemsId', $id);
        $newDB->update('StockOutputitems', [
            'StockOutputItemsUnitPrice' => $data['StockOutputItemsUnitPrice'][$index],
            'StockOutputItemsQty' => $data['StockOutputItemsQty'][$index],
            'StockOutputItemsRemark' => $data['StockOutputItemsRemark'][$index]
        ]);
    }
}
header('Location: Stockout.php');
return;

?>