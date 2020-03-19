<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$data = $_POST;

foreach ($data['GoodsOutputItemsId'] as $index => $id) {
    $newDB->where('GoodsOutputItemsId', $id);
    $item = $newDB->getOne('GoodsOutputitems');
    if($item['GoodsOutputItemsUnitPrice'] != $data['GoodsOutputItemsUnitPrice'][$index] || $item['GoodsOutputItemsQty'] != $data['GoodsOutputItemsQty'][$index] || $item['GoodsOutputItemsRemark'] != $data['GoodsOutputItemsRemark'][$index]){
        //update stock output item
        $newDB->where('GoodsOutputItemsId', $id);
        $newDB->update('GoodsOutputitems', [
            'GoodsOutputItemsUnitPrice' => $data['GoodsOutputItemsUnitPrice'][$index],
            'GoodsOutputItemsQty' => $data['GoodsOutputItemsQty'][$index],
            'GoodsOutputItemsRemark' => $data['GoodsOutputItemsRemark'][$index]
        ]);
    }
}
header('Location: goodsout.php');
return;

?>