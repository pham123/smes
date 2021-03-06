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

          <h1>Tạo mới tài liệu</h1>
          <p>Tạo mới tài liệu, sau khi tạo xong tài liệu đó mới có thể bắt đầu tạo các phiên bản và tải tài liệu lên hệ thống, cũng như thêm các thông tin cần thiết khác.</p>
          <form action="createnewdoc.php" method="post">
          <div class="row">
          <div class="col-md-8">
          <div class="row">
              <div class="col-md-12">
                  <input type="text" name="DocumentName"  placeholder='Tên tài liệu' class='form-control' id="" required>
              </div>
          </div>
          <div class="row">
              <div class="col-md">
                  <span>Bộ phận quản lý</span>
                  <?php makedroplist('Section',1,$user->section); ?>
              </div>    
              <div class="col-md">
                  <span>Loại tài liệu</span>
                  <?php makedroplist('DocumentType'); ?>
              </div>
              <div class="col-md">
                <span>Mã tài liệu</span>
                <input type="text" class="form-control" name="DocumentNumber" required>
              </div>
            </div>

              <div class="row">
                  <div class="col-md">
                      <span>Miêu tả tài liệu</span>
                      <textarea name="DocumentDescription" id="" class='form-control' required></textarea>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md">
                      <button type="submit" class='form-control btn-info'>Create Document</button>
                  </div>
              </div>
              </div>


              <div class="col-md-4">
              <!-- <h3>Danh mục tài liệu hiện tại của bộ phận</h3> -->
              <table class='table table-bordered' id='datatablenotdl' width='100%' cellspacing='0'>
                  <thead>
                      <tr>
                          <th>Danh mục tài liệu đã tồn tại</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $list = $oDB->sl_all('Document',1);
                      foreach ($list as $key => $value) {
                        echo "<tr>
                            <td>".$value['DocumentName']."</td>
                        </tr>";
                      }
                      ?>
                  </tbody>
              </table>
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
