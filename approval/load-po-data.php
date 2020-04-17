<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$id = $_GET['id'];
$newDB->where('PurchaseOrdersId', $id);
$po = $newDB->getOne('purchaseorders');


$arr['supplier'] = $newDB->where('SupplyChainObjectId', $po['SupplyChainObjectId'])->getOne('supplychainobject');

$arr['items'] = $newDB->where('pi.PurchasesId', $po['PurchasesId'])->join('products p', 'p.ProductsId=pi.ProductsId')->get('purchaseitems pi', null, 'pi.*,p.ProductsName,p.ProductsNumber,p.ProductsUnit');
$arr['PurchasesId'] = $po['PurchasesId'];
$arr['PurchaseOrdersId'] = $po['PurchaseOrdersId'];
$arr['PurchaseOrdersFileName'] = $po['PurchaseOrdersFileName'];
echo json_encode($arr);
return;
?>