<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

$name = $_GET['name'];
$id= $_GET['id'];
$value = $_GET['value'];
if(!$id)
return;

$newDB->where('StockInputsId', $id);
$newDB->update('StockInputs', [
    'StockInputs'.$name => $value,
    'StockInputsStatus' => 2
]);

return;

?>