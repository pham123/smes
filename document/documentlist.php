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

        <div class="row">
          <div class="col-md-3">
          <table class='table table-bordered' id='datatablenotdl' width='100%' cellspacing='0'>
            <thead>
                <tr> 
                  <th>#</th>
                  <th>Bộ phận</th>
                </tr>
            </thead>

            <tbody>
              <tr>
                <td>1</td>
                <td><a href='documentlist.php'>All</a></td>
              </tr>
            <?php 
                  $list = $oDB->sl_all('Section',1);
                  foreach ($list as $key => $value) {
                    echo "<tr>
                        <td>".($key+2)."</td>
                        <td><a href='documentlist.php?id=".$value['SectionId']."'>".$value['SectionName']."</a></td>
                    </tr>";
                  }
                  ?>
            </tbody>
            
            </table>
          </div>

          <div class="col-md-9">
            <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
            <thead>
                <tr>
                  <th>Mã tài liệu</th>
                  <th>Tên tài liệu</th>
                  <th>Miêu tả</th>
                  <th>Phiên bản</th>
                  <th>Ngày cập nhật</th>
                  <th>Edit</th>
                </tr>
            </thead>

            <tbody>
                  <?php

                  $where = (isset($_GET['id'])&&is_numeric($_GET['id'])) ? 'SectionId = '.safe($_GET['id']) : 1 ;
                  $list = $oDB->sl_all('Document',$where);
                  foreach ($list as $key => $value) {
                    echo "<tr>
                        <td>".$value['DocumentNumber']."</td>
                        <td>".$value['DocumentName']."</td>
                        <td style='width:40%'>".$value['DocumentDescription']."</td>
                        <td></td>
                        <td></td>";
                    if ($_SESSION[_site_]['userid']==$value['UsersId']) {
                      if($value['DocumentSubmit'] == 1){
                        echo "<td><a href='editdoc.php?id=".$value['DocumentId']."'><i class='fas fa-eye'></i></a></td>";
                      }else{
                        echo "<td><a href='editdoc.php?id=".$value['DocumentId']."'><i class='fas fa-pencil-alt'></i></a></td>";
                      }
                    }else{
                      echo "<td></td>";
                    }
                   
                    echo "</tr>";
                  }
                  ?>

            </tbody>
    
            </table>
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
