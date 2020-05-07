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
    $purchaseid = $_POST['PurchasesId'];
    $total = count($_FILES['fileToUpload']['name']);
    //delete all old files
    if($total > 0){
      $files = glob('quotation/'.$purchaseid.'/*'); // get all file names
      // foreach($files as $file){ // iterate files
      //   if(is_file($file))
      //     unlink($file); // delete file
      // }
    }
    //save new files

    for( $i=0 ; $i < $total ; $i++ ) {
      $tmpFilePath = $_FILES['fileToUpload']['tmp_name'][$i];
      if ($tmpFilePath != ""){
        mkdir('quotation/'.$purchaseid);
        move_uploaded_file($tmpFilePath, 'quotation/' .$purchaseid.'/'.$_FILES['fileToUpload']['name'][$i]);
      }
    }
  }
  header('Location:purchaselist.php');
  exit();
}

//handle get
if(isset($_GET['id'])){
  $newDB->where('PurchasesId', $_GET['id']);
  $purchase = $newDB->getOne('purchases');
  if(!$purchase){
    header('Location:../404.html');
    return;
  }
  if($purchase['UsersId'] != $_SESSION[_site_]['userid']){
    header('Location:../403.php');
    return;
  }

}else{
  header('Location:../404.html');
  return;
}
$suppliers = $newDB->get('supplychainobject');
$purchaseitems = $newDB->where('PurchasesId', $purchase['PurchasesId'])
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
          <h5 class="text-center"><strong>ATTACH QUOTATION FILES</strong></h5>
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="PurchasesId" value="<?php echo $purchase['PurchasesId']?>">
            <table style="width: 90%;" class="mx-auto" id="one">
              <tr>
                <th>BP yêu cầu</th>
                <td>
                <?php echo $newDB->where('SectionId', $purchase['RequestSectionId'])->getOne('section')['SectionName']?>
                </td>
                <th>Công đoạn sử dụng</th>
                <td>
                <?php echo $newDB->where('TraceStationId', $purchase['TraceStationId'])->getOne('tracestation')['TraceStationName']?>
                </td>
              </tr>
              <tr>
                <th>Ngày yêu cầu</th>
                <td><?php echo $purchase['PurchasesDate']?></td>
                <th>PR NO</th>
                <td>
                <?php echo $purchase['PurchasesNo'] ?>
              </tr>
              <tr>
                <th>Urgent</th>
                <td>
                <?php echo $purchase['IsUrgent'] == 0 ? 'No' : 'Yes' ?>
                <th>Comment</th>
                <td>
                  <?php echo $purchase['PurchasesComment'] ?>
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
                    <th><strong>계량 일 (ETA)</strong></th>
                    <th><strong>수량 (Qty)</strong></th>
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
                    <td><?php echo $value['PurchasesRemark']?></td>
                  </tr>
                <?php
                }
                ?>
                </tbody>
              </table>
            </div>
            <div class="row">
              <div class="col-8">
                <label for=""><strong>Current quotation files:<br></strong><?php
                if(!is_dir('quotation/'.$purchase['PurchasesId'])){
                  echo 'chưa có';
                }else{
                  if($files = glob('quotation/'.$purchase['PurchasesId'].'/*')){
                    foreach($files as $index => $file){
                      echo '<span><a href="/smes/purchase/'.$file.'" target="_blank">'.($index+1).'. '.$file.'</a><a href="#" id="remove-file" data-file="'.$file.'" class="text-danger ml-3"><i class="fas fa-trash"></i></a><br></span>';
                    }
                  }else{
                    echo 'chưa có';
                  }
                }
                ?></label>
                <br>
                <label>Upload file:</label>
                <input type="file" name="fileToUpload[]" multiple="multiple" />
              </div>
              <div class="col-4 d-flex">
                <input type="submit" class="btn btn-primary mt-auto" value="save" />
                <a href="print-purchase.php?id=<?php echo $purchase['PurchasesId']?>" target="_blank" class="btn btn-secondary mt-auto"><i class="fas fa-print"></i></a>
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
  <script>
    $(function(){
      $('#remove-file').click(function(){
        let file = $(this).data('file');
        $.ajax({
          type: 'post',
          data: {
            action: 'deletefile',
            file: file
          },
          url: 'deletepofile.php',
          success: () => {
            $(this).closest('span').remove();
          }
        })
      });
    })
  </script>

</body>

</html>
