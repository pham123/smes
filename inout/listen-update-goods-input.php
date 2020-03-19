<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$data = $_POST;

foreach ($data['GoodsInputItemsId'] as $index => $id) {
    $newDB->where('GoodsInputItemsId', $id);
    $item = $newDB->getOne('GoodsInputitems');
    if($item['GoodsInputItemsUnitPrice'] != $data['GoodsInputItemsUnitPrice'][$index] || $item['GoodsInputItemsQty'] != $data['GoodsInputItemsQty'][$index] || $item['GoodsInputItemsRemark'] != $data['GoodsInputItemsRemark'][$index]){
        //update stock output item
        $newDB->where('GoodsInputItemsId', $id);
        $newDB->update('GoodsInputitems', [
            'GoodsInputItemsUnitPrice' => $data['GoodsInputItemsUnitPrice'][$index],
            'GoodsInputItemsQty' => $data['GoodsInputItemsQty'][$index],
            'GoodsInputItemsRemark' => $data['GoodsInputItemsRemark'][$index]
        ]);
    }
}
header('Location: goodsin.php');
return;

?>