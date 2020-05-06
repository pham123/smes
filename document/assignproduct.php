<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/MysqliDb.php');
require('../function/sdb.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$pagetitle = $user->module;
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 250px;overflow: hidden;font-size: 14px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$sDB = new sdb();
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);

function findLastApprovalDocumentDetail($documentid){
  global $newDB;
  $newDB->where('DocumentId', $documentid);
  $documentdetails = $newDB->orderBy('DocumentDetailId', 'desc')->get('documentdetail');
  foreach($documentdetails as $key => $detail){
    $lastline = $newDB->where('DocumentDetailId', $detail['DocumentDetailId'])->orderBy('DocumentDetailLineApprovalId', 'desc')->getOne('documentdetaillineapproval');
    if($lastline['DocumentDetailLineApprovalStatus'] == 2){
      return $detail;
    }
  }
  return null;
}
?>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">

  <?php require('sidebar.php') ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
        
        <!-- Topbar -->
        <?php require('navbar.php') ?>

        <div class="container">
          <!-- Begin card -->
          <h1>Danh sách sản phẩm</h1>
          <form action="" method="get">
          <div class="row">
          <div class="col-md-4">
          <?php
          $selected = (isset($_GET['ProductsId'])) ? safe($_GET['ProductsId']) : null ;
          makedroplist('Products',$where=1,$selected,$width='100%');
          ?>
          </div>
          <div class="col-md-4">
          <button type="submit" class='form-control'>Submit</button>
          </div>
          </div>
          </form>
          <!-- End card -->
          
          <?php
          if (isset($_GET['ProductsId'])) {
            ?>
          <h3>Danh mục tài liệu liên quan</h3>

          
          <table class="table">
            <tr>
              <th>Tên tài liệu</th>
              <th>Ngày thêm vào</th>
            </tr>
            <?php
              $sql = "SELECT ad.*, dc2.DocumentName as child FROM DocumentAP ad
              INNER JOIN Document dc2 ON dc2.DocumentId = ad.RelatedDocumentId
              Where ad.ProductsId=?";
              $list = $sDB->query($sql,$_GET['ProductsId'])->fetchAll();
              // var_dump($list);
              foreach ($list as $key => $value) {
                echo "<tr>";
                echo "<td>".$value['child']."</td>";
                echo "<td>".$value['DocumentAPCreateDate']."</td>";
                echo "</tr>";

              }
            ?>

            <tr>
              
              <td>
              <form method="post" action="assignp.php">
              <input type="hidden" name="mother" value='<?php echo $_GET['ProductsId']?>'>
              <?php
                makedroplist('Document',$where=1,$selected=null,$width='100%');
              ?>
              </td>
              <td>
              <button type="submit" class='form-control'>Thêm tài liệu liên quan</button>
              </td>
              </form>
            </tr>
          </table>
          

            <?php
            } else {
              # code...
            }
            ?>
        
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
