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

        <div class="">
        <h1>Danh sách tiêu chuẩn :</h1>
          <div class="col-md-12">
            <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
            <thead>
                <tr>
                  <th>Tên tiêu chuẩn</th>
                  <th>Mã tài liệu</th>
                  <th>Bộ phận</th>
                  <th style="max-width: 300px;">Miêu tả</th>
                  <th>Phiên bản</th>
                  <th>Ngày cập nhật</th>
                  <th>Download</th>
                </tr>
            </thead>

            <tbody>
                  <?php
                  $sql = "Select * from Document d 
                  inner join section s on s.SectionId=d.SectionId
                  where DocumentTypeId=1";
                  $list2 = $sDB->query($sql)->fetchAll();
                  foreach ($list2 as $key => $value) {
                    $last_document_detail = $newDB->where('DocumentId', $value['DocumentId'])->orderBy('DocumentDetailId', 'DESC')->getOne('documentdetail');
                    $filename = $last_document_detail['DocumentDetailFileName'];
                    $tmp = explode(".", $filename);
                    $ext = end($tmp);
                    echo "<tr>
                        <td><a href='related.php?id=".$value['DocumentId']."'>".$value['DocumentName']."</a></td>
                        <td>".$value['DocumentNumber']."</td>
                        <td>".$value['SectionName']."</td>
                        <td style='width:30%'>".$value['DocumentDescription']."</td>
                        <td>".$last_document_detail['DocumentDetailVersion']."</td>
                        <td>".$last_document_detail['DocumentDetailUpdateDate']."</td>
                        <td style='width: 20%'><a target='_blank' href='files/".$last_document_detail['DocumentDetailId'].'.'.$ext."'>".$last_document_detail['DocumentDetailFileName']."</a></td";
                   
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
