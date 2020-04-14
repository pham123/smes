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
$page_css='th,td{font-weight: normal;font-size: 13px;text-align: center;vertical-align: middle !important;}.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 200px;overflow: hidden;font-size: 13px;}.vs__dropdown-menu li{font-size: 14px;}input::placeholder{font-size: 14px;} .vmoney{width: 100px} table#one th, table#one td{text-align: left;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$shifts = $newDB->get('shift');
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
//handle post
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if(isset($_FILES['fileToUpload'])){
    $purchaseorderid = $_POST['PurchaseOrdersId'];
    $purchaseorder = $newDB->where('PurchaseOrdersId', $purchaseorderid)->getOne('purchaseorders');
    if(file_exists('po/'.$purchaseorderid.'_'.$purchaseorder['PurchaseOrdersFileName'])){
      unlink('po/'.$purchaseorderid.'_'.$purchaseorder['PurchaseOrdersFileName']);
    }
    $filename= $_FILES['fileToUpload']['name'];
    $newDB->where('PurchaseOrdersId', $purchaseorderid)->update('purchaseorders', ['PurchaseOrdersFileName' => $filename]);
    move_uploaded_file($_FILES['fileToUpload']['tmp_name'], 'po/' .$purchaseorderid.'_'.$_FILES['fileToUpload']['name']);
  }
  header('Location:purchaseorderlist.php');
  exit();
}

//handle get
if(isset($_GET['id'])){
  $newDB->where('PurchaseOrdersId', $_GET['id']);
  $purchaseorder = $newDB->getOne('purchaseorders');
  if(!$purchaseorder){
    header('Location:../404.html');
    return;
  }
  if($purchaseorder['UsersId'] != $_SESSION[_site_]['userid']){
    header('Location:../403.php');
    return;
  }

}else{
  header('Location:../404.html');
  return;
}
$suppliers = $newDB->get('supplychainobject');
$purchaseitems = $newDB->where('PurchasesId', $purchaseorder['PurchasesId'])
->join('products p', 'p.ProductsId=pui.ProductsId')->get('purchaseitems pui',null,'p.ProductsNumber,p.ProductsName,p.ProductsUnit,pui.PurchaseItemsId,pui.PurchasesEta,pui.PurchasesRemark,pui.PurchasesQty,pui.PurchaseItemsUnitPrice');

?>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">

  <?php require('sidebar.php') ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        
        <!-- Topbar -->
        <?php require('navbar.php') ?>

        <!-- Begin Page Content -->
        <div class="mx-1">
          <h5 class="text-center"><strong>ATTACH PURCHASE ORDER SCAN FILE</strong></h5>
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="PurchaseOrdersId" value="<?php echo $purchaseorder['PurchaseOrdersId']?>">
            <table style="width: 90%;" class="mx-auto" id="one">
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
              </tr>
              <tr>
                <th> 배송 방식(Shipment Method)</th>
                <td>
                <?php echo $purchaseorder['PurchaseOrdersShipmentMethod'] ?>
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
            <div class="w-100 mt-3" style="overflow: auto;">
              <table class="table table-bordered w-100">
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
            <div class="row">
              <div class="col-sm-6">
                <label style="font-size: 14px;"><strong>Suppliers</strong></label>
                <p><?php echo $purchaseorder['PurchaseOrdersSupplierComment'] ?></p>
                <label for=""><strong>Current scan file:</strong><?php
                if($purchaseorder['PurchaseOrdersFileName']){
                  echo '<a href="po/'.$purchaseorder['PurchaseOrdersId'].'_'.$purchaseorder['PurchaseOrdersFileName'].'" target="_blank">'.$purchaseorder['PurchaseOrdersFileName'].'</a>';
                }else{
                  echo 'Chưa có';
                }
                ?></label>
                <br>
                <label>Upload file:</label>
                <input type="file" name="fileToUpload" />
              </div>
              <div class="col-md-6 d-flex">
                <input type="submit" class="btn btn-primary mt-auto" value="save" />
                <a href="print-po.php?id=<?php echo $purchaseorder['PurchaseOrdersId']?>" target="_blank" class="btn btn-secondary mt-auto"><i class="fas fa-print"></i></a>
              </div>
          </div>
          </form>
        </div>
  

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <?php require('../views/template-footer.php'); ?>

</body>

</html>
