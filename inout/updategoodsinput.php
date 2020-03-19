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
if(!isset($_GET['id'])){
    echo 'KHÔNG HỢP LỆ';
    return;
}
$id = $_GET['id'];
$newDB->where('GoodsInputsId', $id);
$GoodsInput = $newDB->getOne('GoodsInputs');

if($GoodsInput['GoodsInputsStatus'] == 2){
    echo 'BẢO VỆ ĐÃ XÁC NHẬN KHÔNG THỂ SỬA!';
    return;
}

$newDB->where('GoodsInputsId', $id);
$GoodsInputitems = $newDB->get('GoodsInputitems');
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
        table th,td{
            font-weight: normal;
            font-size: 13px;
            text-align: center;
            vertical-align: middle !important;
            border: 1px solid #333;
            }
        table.noborder th, table.noborder td{
            font-weight: normal;
            font-size: 13px;
            text-align: left;
            vertical-align: middle !important;
            border: none;
            }
        table.items td{
            padding: 15px;
            font-size:16px;
        }
    </style>
    <title>Print GoodsInput</title>
  </head>
  <body>
    <div class="ml-2 mt-2">
        <p>
        <img src="../img/hallalogo.png" alt="" style="width:100px">
    </div>
    <h3 class="text-center mb-4">
        STOCK OUTPUT SHEET<br>
        <em style="font-weight: normal;">Phiếu xuất kho</em>
    </h3>
    <div class="row p-2 mx-auto mb-5" style="width: 1000px;">

        <table class="w-100 noborder">
            <tr>
                <th><strong>FROM(TỪ):</strong></th>
                <td><?php echo $newDB->where('SupplyChainObjectId',$GoodsInput['FromId'])->getOne('supplychainobject')['SupplyChainObjectName'] ?></td>
                <th><strong>KHO:</strong></th>
                <td><?php echo $GoodsInput['GoodsInputsType'] ?></td>
                <th><strong>BKS:</strong></th>
                <td><?php echo $GoodsInput['GoodsInputsBks']?></td>
            </tr>
            <tr>
                <th><strong>TO(ĐẾN):</strong></th>
                <td><?php echo $newDB->where('SupplyChainObjectId',$GoodsInput['ToId'])->getOne('supplychainobject')['SupplyChainObjectName'] ?></td>
                <th><strong>NO:</strong></th>
                <td><?php echo $GoodsInput['GoodsInputsNo'] ?></td>
                <th><strong>THỜI GIAN:</strong></th>
                <td><?php echo $GoodsInput['GoodsInputsTime']?></td>
            </tr>
            <tr>
                <th><strong>DELIVERY DATE(NGÀY GIAO HÀNG):</strong></th>
                <td><?php echo $GoodsInput['GoodsInputsDate'] ?></td>
                <th><strong></strong></th>
                <td></td>
                <th><strong>NGƯỜI LẬP:</strong></th>
                <td><?php echo $newDB->where('UsersId', $GoodsInput['UsersId'])->getOne('users')['UsersFullName']?></td>
            </tr>
        </table>
        </div>
        <div class="row px-4">
        <table class="items" style="margin-left: auto; margin-right: auto; width: 1000px;">
            <thead>
                <tr>
                    <th><strong>NO</strong></th>
                    <th style="min-width: 120px;"><strong>Part Name</strong></th>
                    <th style="min-width: 150px;"><strong>Part No</strong></th>
                    <th><strong>W/o</strong></th>
                    <th><strong>Unit price</strong></th>
                    <th><strong>Unit</strong></th>
                    <th><strong>Qty</strong></th>
                    <th><strong>Remark</strong></th>
                </tr>
            </thead>
            <tbody>
                <form method="post" action="listen-update-goods-input.php">
                <?php
                $totalQty = 0;
                foreach($GoodsInputitems as $k=>$item)
                {
                    $totalQty += $item['GoodsInputItemsQty'];
                    $newDB->where('ProductsId', $item['ProductsId']);
                    $product = $newDB->getOne('products');
                ?>
                <tr>
                    <td><?php echo $k+1 ?><input type="hidden" name="GoodsInputItemsId[]" value="<?php echo $item['GoodsInputItemsId'] ?>"</td>
                    <td><?php echo $product['ProductsName'] ?></td>
                    <td><?php echo $product['ProductsNumber']?></td>
                    <td><?php echo $item['GoodsInputItemsWo']?></td>
                    <td><input style="height: 30px;font-size:16px;width:100px;" name="GoodsInputItemsUnitPrice[]" type="number" value="<?php echo $item['GoodsInputItemsUnitPrice']?>"></td>
                    <td><?php echo $product['ProductsUnit']?></td>
                    <td><input style="height: 30px;font-size:16px;width:60px;" name="GoodsInputItemsQty[]" type="number" value="<?php echo $item['GoodsInputItemsQty']?>"></td>
                    <td><input style="height: 30px;font-size:16px;" name="GoodsInputItemsRemark[]" type="text" value="<?php echo $item['GoodsInputItemsRemark']?>"></td>
                </tr>
                <?php
                }
                    $numOfItems = count($GoodsInputitems);
                ?>
                <tr>
                    <td><?php echo $numOfItems+1?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="4"><input class="btn btn-primary btn-sm btn-block" type="submit" value="save"></td>
                </tr>
                </form>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th colspan="2"><strong>SUM</strong></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?php echo $totalQty?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
        <div class="row py-5 px-3">
            <div class="col-12">
                <table class="table-sm" style="width: 1000px;margin-left: auto; margin-right: auto;">
                    <thead>
                        <tr>
                            <th style="width: 200px;"><strong>Delivered by</strong><br>(Người giao hàng)</th>
                            <th style="width: 200px;"><strong>Checked by</strong><br>(Xác nhận)</th>
                            <th style="width: 200px;"><strong>Inspect by</strong><br>(Người kiểm tra)</th>
                            <th style="width: 200px;"><strong>Guard</strong><br>(Bảo vệ)</th>
                            <th style="width: 200px;"><strong>Received by</strong><br>(Người nhận hàng)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 120px;">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="../vendor/jquery/jquery.min.js"></script>
        <script>
            // $(function(){
            //     $('input').on('input',function(e){
            //        $.ajax({
            //            'url': 'updateGoodsInputdata.php?name='+e.target.name+'&value='+e.target.value,
            //            'method': 'get'
            //        })
            //     })
            // })
        </script>

  </body>
</html>