<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

$arr = explode('_', $_GET['name']);
$value = $_GET['value'];
$name= $arr[0];
$id = $arr[1];
if(!$id)
return;

$newDB->where('StockOutputItemsId', $id);
$newDB->update('StockOutputItems', [
    'StockOutputItems'.$name => $value
]);

return;

?>