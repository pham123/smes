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
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 250px;overflow: hidden;font-size: 14px;}';
// $refresh = 5;
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
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

        <div class="container-fluid">
          <form action="listen-index.php" method="post">
          <div class="row w-50 mx-auto">
              <div class="col-md">
                <label>In or Out</label>
                <select name="StockType" class="form-control" required>
                  <option value="1">Nhập hàng</option>
                  <option value="2">Xuất hàng</option>
                </select>
              </div>
              <div class="col-md">
                <label>Material</label>
                <select name="MaterialTypesId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%" required>
                    <option value="">select</option>
                    <?php 
                    $model = $newDB->get('materialtypes');
                    foreach ($model as $key => $value) {
                      echo "<option value='".$value['MaterialTypesId']."'>".$value['MaterialTypesName']."</option>";
                    }
                    ?>
                    
                  </select>
              </div>
              <div class="col-md-12 mt-3">
                <input type="submit" class="btn btn-primary w-50 mx-auto btn-block" value="submit">
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
