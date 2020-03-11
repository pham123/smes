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
      <div id="content">
        
        <!-- Topbar -->
        <?php require('navbar.php') ?>

        <!-- Begin Page Content -->
        <!-- Begin Page Content -->
        <?php
// echo $user->acess();
if ($user->acess()==1||$user->acess()==2) {
  # code...
?>
        <div>
          <form action="" method="post">
            <span>Add new document type: </span>
            <input type="text" name='DocumentTypeName'>
            <button type="submit">Submit</button>
          </form>
          <?php
            if (isset($_POST['DocumentTypeName'])&&$_POST['DocumentTypeName']!='') {
              $sql = "INSERT INTO DocumentType (`DocumentTypeName`,`DocumentTypeDescription`,`DocumentTypeOption`) VALUES (?,?,1)";
              $sDB->query($sql,$_POST['DocumentTypeName'],$_POST['DocumentTypeName']);
              header('location:documenttype.php');
            }
          ?>
        </div>
<?php } ?>
        </br>

        <div>
        <?php
        $sql = "SELECT * FROM DocumentType";
        $result = $sDB->query($sql)->fetchAll();
        // echo "<pre>";
        // var_dump($result);
        // echo "</pre>";

        ?>
        <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
        <thead>
            <tr>
              <th>Id</th>
              <th>Type Name</th>
              <th>Document Code</th>
              <th>Document Name Vi</th>
              <th>Document Description</th>
              <th>Edit</th>
            </tr>
        </thead>

        <tbody>
        <?php
        foreach ($result as $key => $value) {
          # code...

        ?>
            <tr>
              <td><?php echo $value['DocumentTypeId'] ?></td>
              <td><?php echo $value['DocumentTypeName'] ?></td>
              <td><?php echo $value['DocumentTypeCode'] ?></td>
              <td><?php echo $value['DocumentTypeNameVi'] ?></td>
              <td><?php echo $value['DocumentTypeDescription'] ?></td>
              <td>
              <?php
              if ($user->acess()==1||$user->acess()==2) {
                echo "<a href='editdocumenttype.php?id=".$value['DocumentTypeId']."'>Edit</a>";
              }
              ?>
              
              </td>
            </tr>
        </tbody>
        <?php
                }
        ?>
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
