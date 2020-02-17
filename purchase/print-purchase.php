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
$newDB->where('PurchasesId', $id);
$purchase = $newDB->getOne('purchases');

$newDB->where('PurchasesId', $id);
$purchaseitems = $newDB->get('purchaseitems');
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
            font-size: 13px;
            text-align: center;
            vertical-align: middle !important;
            border: 1px solid #333;
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
        <strong>Halla Electronics Vina Co, Ltd</strong></p>
    </div>
    <h3 class="text-center mb-4">
        PURCHASE REQUEST<br>
        <em style="font-weight: normal;">Yêu cầu mua hàng</em>
    </h3>
    <div class="row p-2">
        <label style="font-size: 14px;" class="col-sm-2"><strong>Request Dept/</strong><em>Bp yêu cầu:</em></label>
        <div class="col-sm-2" style="font-size: 14px;">
            <?php echo $newDB->where('SectionId',$purchase['RequestSectionId'])->getOne('section')['SectionName'] ?>
        </div>
        <label style="font-size: 14px;" class="col-sm-2"><strong>Using For/</strong><em>Công đoạn sd:</em></label>
        <div class="col-sm-2" style="font-size: 14px;">
            <?php echo $newDB->where('TraceStationId',$purchase['TraceStationId'])->getOne('tracestation')['TraceStationName'] ?>
        </div>
        <label style="font-size: 14px;" class="col-sm-2"><strong>Request Date/</strong><em>Ngày:</em></label>
        <div class="col-sm-2" style="font-size: 14px;">
            <?php echo $purchase['PurchasesDate'] ?>
        </div>
        <label style="font-size: 14px;" class="col-sm-2"><strong>Received Dept/</strong><em>Bp nhận:</em></label>
        <div class="col-sm-2" style="font-size: 14px;">
            <?php echo $newDB->where('SectionId',$purchase['ReceiveSectionId'])->getOne('section')['SectionName'] ?>
        </div>
        <label style="font-size: 14px;" class="col-sm-2"><strong>Urgent/</strong><em>Khẩn cấp:</em></label>
        <div class="col-sm-2" style="font-size: 14px;">
            <?php 
             if($purchase['IsUrgent'] == 0)
             {
                 echo 'No';
             }else{
                 echo 'Yes';
             }
            ?>
        </div>
        <label style="font-size: 14px;" class="col-sm-2"><strong>PR No/</strong><em>Số PR:</em></label>
        <div class="col-sm-2" style="font-size: 14px;">
            <?php echo $purchase['PurchasesNo'] ?>
        </div>
        <table style="margin-left: 10px; margin-right: 10px;">
            <thead>
                <tr>
                <th rowspan="2"><strong>NO</strong><br><em>STT</em></th>
                <th rowspan="2" style="min-width: 120px;"><strong>HALLA'S CODE</strong><br>Mã hàng</th>
                <th rowspan="2" style="min-width: 150px;"><strong>VIETNAMESE NAME</strong><br><em>Tên tiếng Việt</em></th>
                <th rowspan="2" style="min-width: 150px;"><strong>ENGLISH NAME</strong><br><em>Tên tiếng Anh</em></th>
                <th rowspan="2"><strong>PICTURE</strong><br><em>Hình ảnh</em></th>
                <th colspan="5"><strong>SPECIFICATION</strong><br><em>Thông số kỹ thuật</em></th>
                <th rowspan="2"><strong>QUANTITY</strong><br><em>Số lượng yc<em></th>
                <th rowspan="2"><strong>CURRENT STOCK</strong><br><em>Tồn kho hiện tại</em></th>
                <th rowspan="2"><strong>AVERAGE USING/MONTH</strong><br><em>Lượng sử dụng trung bình/tháng</em></th>
                <th rowspan="2"><strong>UNIT</strong><br><em>Đơn vị</em></th>
                <th rowspan="2"><strong>ETA</strong><br><em>Ngày cân hàng</em></th>
                <th rowspan="2"><strong>REMAX</strong><br><em>Ghi chú</em></th>
                </tr>
                <tr>
                <th><strong>Manufacturer's code</strong><br><em>Mã của NSX<em></th>
                <th><strong>Size</strong><br><em>Kích thước<em></th>
                <th><strong>Color</strong><br><em>Màu sắc<em></th>
                <th><strong>Material</strong><br><em>Vật liệu<em></th>
                <th><strong>Manufacturer/Original country</strong><br><em>Nhà sx/Xuất sứ<em></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalQty = 0;
                foreach($purchaseitems as $k=>$item)
                {
                    $totalQty += $item['PurchasesQty'];
                    $newDB->where('ProductsId', $item['ProductsId']);
                    $product = $newDB->getOne('products');
                ?>
                <tr>
                    <td><?php echo $k+1 ?></td>
                    <td><?php echo $product['ProductsNumber'] ?></td>
                    <td><?php echo $product['ProductsName']?></td>
                    <td><?php echo $product['ProductsEngName']?></td>
                    <td><?php if(file_exists("../products/image/small/".$product['ProductsId'].'.jpg')){
                        ?>
                        <img src="../products/image/small/<?php echo $product['ProductsId']?>.jpg" style="max-width: 30px;">
                        <?php
                        }
                        ?>
                        </td>
                    <td><?php echo $item['ManufacturerCode']?></td>
                    <td><?php echo $product['ProductsSize']?></td>
                    <td><?php echo $product['ProductsColor']?></td>
                    <td><?php echo $product['ProductsMaterial']?></td>
                    <td><?php echo $item['ManufacturerName']?></td>
                    <td><?php echo $item['PurchasesQty']?></td>
                    <td><?php echo $product['ProductsStock']?></td>
                    <td>AVERAGE USING</td>
                    <td><?php echo $product['ProductsUnit']?></td>
                    <td><?php echo $item['PurchasesEta']?></td>
                    <td><?php echo $item['PurchasesRemark']?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="10"><strong>Total</strong></th>
                    <th><?php echo $totalQty?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <div class="row py-5 px-3">
            <div class="col-6">
                Comment of manager:<br>
                <p>..........................................................................................................................................................................</p>
                <p>..........................................................................................................................................................................</p>
                <p>..........................................................................................................................................................................</p>
            </div>
            <div class="col-6">
                <img src="./purchase-sign.png" alt="" style="max-width: 100%;">
            </div>
        </div>
    </div>

  </body>
</html>