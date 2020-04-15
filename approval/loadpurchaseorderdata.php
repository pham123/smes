<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

$arr = [];

//FIND NOT SUBMIT PURCHASE ORDER
$newDB->where('PurchasesId', $_GET['id']);
$n_submit_po = $newDB->getOne('PurchaseOrders');

if($n_submit_po){
    $arr['PurchaseOrdersId'] = $n_submit_po['PurchaseOrdersId'];
    $arr['SupplyChainObjectId'] = $n_submit_po['SupplyChainObjectId'];
    $arr['PurchaseOrdersNo'] = $n_submit_po['PurchaseOrdersNo'];
    $arr['PurchaseOrdersDate'] = $n_submit_po['PurchaseOrdersDate'];
    $arr['PurchaseOrdersShipmentMethod'] = $n_submit_po['PurchaseOrdersShipmentMethod'];
    $arr['PurchaseOrdersCurrency'] = $n_submit_po['PurchaseOrdersCurrency'];
    $arr['PurchaseOrdersPlateDischarge'] = $n_submit_po['PurchaseOrdersPlateDischarge'];
    $arr['PurchaseOrdersPaymentTerm'] = $n_submit_po['PurchaseOrdersPaymentTerm'];
    $arr['PurchaseOrdersPage'] = $n_submit_po['PurchaseOrdersPage'];
    $arr['PurchaseOrdersMovingPlan'] = $n_submit_po['PurchaseOrdersMovingPlan'];
    $arr['PurchaseOrdersSupplierComment'] = $n_submit_po['PurchaseOrdersSupplierComment'];
    $arr['PurchaseOrdersStatus'] = $n_submit_po['PurchaseOrdersStatus'];
}else{
    $PO_data = [
        'PurchasesId' => $_GET['id'],
        'SupplyChainObjectId' => null,
        'PurchaseOrdersNo' => '',
        'PurchaseOrdersDate' => null,
        'PurchaseOrdersShipmentMethod' => '',
        'PurchaseOrdersCurrency' => '',
        'PurchaseOrdersPlateDischarge' => '',
        'PurchaseOrdersPaymentTerm' => '',
        'PurchaseOrdersPage' => '',
        'PurchaseOrdersMovingPlan' => '',
        'PurchaseOrdersSupplierComment' => '',
        'PurchaseOrdersStatus' => 0,
        'UsersId' => $_SESSION[_site_]['userid'],
    ];
    $PO_id = $newDB->insert('PurchaseOrders',$PO_data);

    $newDB->where('PurchaseOrdersId', $PO_id);
    $last_PO = $newDB->getOne('PurchaseOrders');
    
    $arr['PurchaseOrdersId'] = $last_PO['PurchaseOrdersId'];
    $arr['SupplyChainObjectId'] = $last_PO['SupplyChainObjectId'];
    $arr['PurchaseOrdersNo'] = $last_PO['PurchaseOrdersNo'];
    $arr['PurchaseOrdersDate'] = $last_PO['PurchaseOrdersDate'];
    $arr['PurchaseOrdersShipmentMethod'] = $last_PO['PurchaseOrdersShipmentMethod'];
    $arr['PurchaseOrdersCurrency'] = $last_PO['PurchaseOrdersCurrency'];
    $arr['PurchaseOrdersPlateDischarge'] = $last_PO['PurchaseOrdersPlateDischarge'];
    $arr['PurchaseOrdersPaymentTerm'] = $last_PO['PurchaseOrdersPaymentTerm'];
    $arr['PurchaseOrdersPage'] = $last_PO['PurchaseOrdersPage'];
    $arr['PurchaseOrdersMovingPlan'] = $last_PO['PurchaseOrdersMovingPlan'];
    $arr['PurchaseOrdersSupplierComment'] = $last_PO['PurchaseOrdersSupplierComment'];
    $arr['PurchaseOrdersStatus'] = $last_PO['PurchaseOrdersStatus'];

}


echo json_encode($arr);
return;
?>