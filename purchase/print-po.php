<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/MysqliDb.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$pagetitle = $user->module;
require('../function/template.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$id = $_GET['id'];
$newDB->where('PurchaseOrdersId', $id);
$purchaseorder = $newDB->getOne('purchaseorders');

$purchaseitems = $newDB->where('PurchasesId', $purchaseorder['PurchasesId'])
->join('products p', 'p.ProductsId=pui.ProductsId')->get('purchaseitems pui',null,'p.ProductsNumber,p.ProductsName,p.ProductsUnit,pui.PurchaseItemsId,pui.PurchasesEta,pui.PurchasesRemark,pui.PurchasesQty,pui.PurchaseItemsUnitPrice');
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        th,td{
            font-weight: normal;
            text-align: center;
            vertical-align: middle !important;
            border: 1px solid #333;
            padding: 7px 5px;
            }
        table#one th{
            text-align: left;
            border-right: 1px dotted #333;
            border-left: 1px solid #333;
            border-bottom: 1px dotted #333;
            border-top: 1px dotted #333;
        }
        table#one td{
            text-align: left;
            border-left: none;
            border-right: 1px solid #333;
            border-bottom: 1px dotted #333;
            border-top: 1px dotted #333;
        }
        table#one tr:first-child {
            border-top: 1px solid #333;
        }
        table#one tr:last-child{
            border-bottom: 1px solid #333;
        } 
    </style>
    <style type="text/css" media="print">
        @page { 
            size: landscape;
        }
    </style>
    <title>Print purchase</title>
  </head>
  <body onload="window.print()">
    <div class="ml-2 mt-2">
        <p>
        <img src="../img/hallalogo.png" alt="" style="width:100px">
        <strong>회사: HALLA ELECTRONICS VINA CO.,LTD</strong></p>
    </div>
    <h3 class="text-center mb-3">
    품의서(PURCHASE ORDER)
    </h3>
    <div class="row p-2">
        <div style="width: 90%;" class="mx-auto">
        <span>회사: HALLA ELECTRONICS VINA CO.,LTD</span><br>
        <span>주소:  Lot L4, Trang Due Industrial Park, Hong Phong Commune, An Duong District, Hai Phong City, Vietnam</span>
        <table class="w-100" id="one">
                <tr>
                    <th>업체 (Supplier Name)</th>
                    <td>
                        <?php echo $newDB->where('SupplyChainObjectId', $purchaseorder['SupplyChainObjectId'])->getOne('supplychainobject')['SupplyChainObjectName']?>
                    </td>
                    <th>PO 번호(No)</th>
                    <td>
                    <?php echo $purchaseorder['PurchaseOrdersNo'] ?>
                    </td>
                </tr>
                <tr>
                    <th> 업체 코드(Supplier Code)</th>
                    <td></td>
                    <th>PO 날짜(Date)	</th>
                    <td>
                    <?php echo $purchaseorder['PurchaseOrdersDate'] ?>
                    </td>
                </tr>
                <tr>
                    <th> 배송 방식(Shipment Method)</th>
                    <td>
                    <?php echo $purchaseorder['PurchaseOrdersShipmentMethod'] ?>
                    </td>
                    <th>통화(Currency)</th>
                    <td>
                    <?php echo $purchaseorder['PurchaseOrdersCurrency'] ?>
                    </td>
                </tr>
                <tr>
                    <th> 인도 장소(Plate of discharge)</th>
                    <td>
                    <?php echo $purchaseorder['PurchaseOrdersPlateDischarge'] ?>
                    </td>
                    <th>지불방식(Payment Term)</th>
                    <td>
                    <?php echo $purchaseorder['PurchaseOrdersPaymentTerm'] ?>
                    </td>
                </tr>
                <tr>
                    <th> 페이지(Page)</th>
                    <td>
                    <?php echo $purchaseorder['PurchaseOrdersPage'] ?>
                    </td>
                    <th>물동량(Moving Plan)</th>
                    <td>
                    <?php echo $purchaseorder['PurchaseOrdersMovingPlan'] ?>
                    </td>
                </tr>
                </table>
        </div>
                <div style="width: 90%; margin: 0 auto; margin-top: 30px;">
              <span>구매항목(Purchase items)</span>
              <table class="w-100">
                <thead>
                  <tr>
                    <th><strong>순번(No)</strong></th>
                    <th><strong>품명 (Part Name)</strong></th>
                    <th><strong>코드(Part No)</strong></th>
                    <th><strong>단위 (UNIT)</strong></th>
                    <th><strong>입고 (Need By Date)</strong></th>
                    <th><strong>수량 (Qty)</strong></th>
                    <th><strong>단가 (Price)</strong></th>
                    <th><strong>금액 (Amount)</strong></th>
                    <th><strong>비고(Note)</strong></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $totalAmount = 0;
                    foreach ($purchaseitems as $key => $value) {
                        $totalAmount += $value['PurchasesQty'] * $value['PurchaseItemsUnitPrice'];
                ?>
                  <tr>
                    <td><?php echo $key+1?></td>
                    <td><?php echo $value['ProductsName']?></td>
                    <td><?php echo $value['ProductsNumber']?></td>
                    <td><?php echo $value['ProductsUnit']?></td>
                    <td><?php echo $value['PurchasesEta']?></td>
                    <td><?php echo $value['PurchasesQty']?></td>
                    <td><?php echo number_format($value['PurchaseItemsUnitPrice'], 0, ',', '.')?></td>
                    <td><strong><?php echo number_format($value['PurchaseItemsUnitPrice']*$value['PurchasesQty'], 0, ',', '.')?></strong></td>
                    <td><?php echo $value['PurchasesRemark']?></td>
                  </tr>
                <?php
                }
                ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="5"><strong>금액 (TOTAL)</strong></th>
                    <th></th>
                    <th></th>
                    <th><strong><?php echo number_format($totalAmount, 0, ',', '.') ?></strong></th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
                </div>
            </div>
            <div class="row mx-auto mt-3" style="width: 90%;">
                <div class="col-8" style="padding-left: 0;">
                    <strong>HALLA ELECTRONICS VINA</strong>
                    <table class="table-sm w-100">
                        <thead>
                            <tr>
                                <th><strong>Created by</strong></th>
                                <th><strong>Approval 1</strong></th>
                                <th><strong>Approval 2</strong></th>
                                <th><strong>Approval 3</strong></th>
                                <th><strong>Approval 4</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="height: 100px;">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-4">
                    <strong>업체 (SUPPLIER)</strong><br>
                </div>
            </div>

  </body>
</html>