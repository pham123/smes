<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('../config.php');
include('../function/db_lib.php');

$oDB = new db();
$sql[] = "CREATE TABLE PurchaseOrders (
    PurchaseOrdersId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    PurchasesId int(9) NOT NULL,
    SupplyChainObjectId int(9),
    PurchaseOrdersNo varchar(100),
    PurchaseOrdersDate date,
    PurchaseOrdersShipmentMethod varchar(100),
    PurchaseOrdersCurrency varchar(20),
    PurchaseOrdersPlateDischarge varchar(100),
    PurchaseOrdersPaymentTerm varchar(100),
    PurchaseOrdersPage varchar(20),
    PurchaseOrdersMovingPlan varchar(50),
    PurchaseOrdersSupplierComment varchar(255),
    PurchaseOrdersStatus tinyint,
    UsersId INT(9) not null,
    CreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    UpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);";
$sql[]="ALTER TABLE PurchaseItems ADD PurchaseItemsUnitPrice int(9);";
$sql[]="ALTER TABLE PurchaseOrders ADD PurchaseOrdersFileName varchar(255);";
$sql[] = "ALTER TABLE StockInputItems MODIFY StockInputItemsQty float;";
$sql[] = "ALTER TABLE StockOutputItems MODIFY StockOutputItemsQty float;";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

