<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/sdb.php');
require('../function/function.php');
require('../function/MysqliDb.php');
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
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
if (isset($_GET['id'])) {
  # code...
}else{
  header('Location:index.php');
  exit();
}
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

        <div>
        <?php
        $sql = "SELECT * FROM DocumentType where DocumentTypeId=?";
        $result = $sDB->query($sql,safe($_GET['id']))->fetchArray();
        // echo "<pre>";
        // var_dump($result);
        // echo "</pre>";

        ?>
        <form action="listeneditdocumenttype.php" method="post">
        <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
        <thead>
            <tr>
              <th>Id</th>
              <th>Type Name</th>
              <th>Document Type Description</th>
              <th>Name Vi</th>
              <th>Document Code</th>
              <th>Edit</th>
            </tr>
        </thead>

        <tbody>
        

            <tr>
              <td><?php echo $result['DocumentTypeId'] ?>
              <input type="hidden" name="DocumentTypeId" value='<?php echo $result['DocumentTypeId'] ?>'>
              </td>
              <td>
              <input type="text" class='form-control' name="DocumentTypeName" id="" value='<?php echo $result['DocumentTypeName'] ?>'>
              </td>
              <td>
              <input type="text"  class='form-control' name="DocumentTypeDescription" id="" value='<?php echo $result['DocumentTypeDescription'] ?>'>
              </td>
              <td>
              <input type="text"  class='form-control' name="DocumentTypeNameVi" id="" value='<?php echo $result['DocumentTypeNameVi'] ?>'>
              </td>
              <td>
              <input type="text"  class='form-control' name="DocumentTypeCode" id="" value='<?php echo $result['DocumentTypeCode'] ?>'>
              </td>

              <td><button type="submit">Update</button></td>
            </tr>
        
        </tbody>
        </table>
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
