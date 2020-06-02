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
        <h1>Đăng kí khách vào cổng</h1>
        <div class="row">
          <div class="col-md-4">
            <form action="" method="post">
              <h3>Form here</h3>
              <input type="text" name="congty" class="form-control" placeholder="Tên công ty">
              <span>Tên khách</span>
<textarea name="" id="" class="form-control" placeholder="Thông tin khách" rows="3">1,Name: Nguyễn Văn A ; CMT: 012345678
2,Name: Nguyễn Văn B ; CMT: 012345678
3,Name: Nguyễn Văn C ; CMT: 012345678
</textarea>
<span>Lí do</span>
<textarea name="" id="" class="form-control" rows="3" placeholder="Lý do vào cổng">Gặp ai?
Gặp để làm gì?
</textarea>
<span>Ngày vào</span>
<input type="date" class="form-control" name="ngayvao" id="">
<input type="text" name="" id="" class="form-control" placeholder="Giờ vào : vd 14:30 ">
<button type="submit" class='form-control'>Đăng kí</button>
            </form>
          </div>

          <div class="col-md-8">
            <h1>List here</h1>
          </div>

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
