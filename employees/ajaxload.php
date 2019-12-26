<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$newDB->where('ProductsOption', 4);
$spare_arr = $newDB->get('products',null,'ProductsId,ProductsNumber,ProductsName,ProductsUnit,ProductsStock');
$arr = [];
$arr['products_data'] = $spare_arr;
$arr[] = $spare_arr;
echo json_encode($arr);
return;
?>