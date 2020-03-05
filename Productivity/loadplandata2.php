<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

$stations = $newDB->get('tracestation');
$shifts = $newDB->get('shift');

$arr['tracestations'] = $stations;
$arr['shifts'] = $shifts;

$newDB->join('products s','p.ProductsId = s.ProductsId', 'LEFT');
$products = $newDB->get('proplan p', null, 'distinct p.ProductsId,s.ProductsName,s.ProductsNumber');
$arr['products'] = $products;

echo json_encode($arr);
return;
?>