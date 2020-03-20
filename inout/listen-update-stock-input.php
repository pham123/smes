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
    if($item['StockInputItemsUnitPrice'] != $data['StockInputItemsUnitPrice'][$index] || $item['StockInputItemsQty'] != $data['StockInputItemsQty'][$index] || $item['StockInputItemsRemark'] != $data['StockInputItemsRemark'][$index]){
        //update stock output item
        $newDB->where('StockInputItemsId', $id);
        $newDB->update('StockInputitems', [
            'StockInputItemsUnitPrice' => $data['StockInputItemsUnitPrice'][$index],
            'StockInputItemsQty' => $data['StockInputItemsQty'][$index],
            'StockInputItemsRemark' => $data['StockInputItemsRemark'][$index]
        ]);
    }
}
header('Location: Stockin.php');
return;

?>