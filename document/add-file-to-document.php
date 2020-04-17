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
$id=$_GET['id'];
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
$doc = $newDB->where('DocumentId', $id)->getOne('document');

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

          <h3>Tạo mới phiên bản</h3>
          <form action="listen-add-file.php?id=<?php echo $id?>" method="post" enctype="multipart/form-data">
          <div class="row">
          <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <strong>Mã tài liệu:&nbsp;</strong><?php echo $doc['DocumentNumber'] ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <strong>Tên tài liệu:&nbsp;</strong><?php echo $doc['DocumentName'] ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <strong>Bộ phận quản lý:&nbsp;</strong>
                    <?php echo $newDB->where('SectionId', $doc['SectionId'])->getOne('section')['SectionName'] ?>
                </div>    
                <div class="col-md">
                    <strong>Kiểu tài liệu:&nbsp;</strong>
                    <?php echo $newDB->where('DocumentTypeId', $doc['DocumentTypeId'])->getOne('documenttype')['DocumentTypeName'] ?>
                </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <strong>Mô tả tài liệu:</strong>
                <?php echo $doc['DocumentDescription'] ?>
              </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <strong>File:&nbsp;</strong>
                    <input type="file" name="fileToUpload" required>
                </div>    
                <div class="col-md">
                    <strong>Version:&nbsp;</strong>
                    <input type="text" name="DocumentDetailVersion" required>
                </div>
            </div>

            <div class="row my-2">
                <div class="col-md">
                    <span>Mô tả version:</span>
                    <textarea name="DocumentDetailDesc" id="" class='form-control'></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md">
                    <button type="submit" class='w-50 btn-block mx-auto btn-primary'>create new version</button>
                </div>
            </div>
          </div>


          <div class="col-md-4">
          <!-- <h3>Danh mục tài liệu hiện tại của bộ phận</h3> -->
            <table class='table table-bordered' id='datatablenotdl' width='100%' cellspacing='0'>
                <thead>
                    <tr>
                        <th>Phiên bản</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                  <?php 
                    $list = $newDB->where('DocumentId',$id)->get('documentdetail');
                    foreach ($list as $key => $value) {
                      $filename = $value['DocumentDetailFileName'];
                      $tmp = explode(".", $filename);
                      $ext = end($tmp);
                      echo "<tr>
                          <td>".$value['DocumentDetailVersion']."</td>
                          <td><a target='_blank' href='files\\".$value['DocumentDetailId'].".".$ext."'>".$value['DocumentDetailFileName']."</a></td>
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
