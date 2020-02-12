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
$pagetitle =basename(dirname(__FILE__));
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);

$sid = $_GET['sid'];
$newDB->where('SectionId', $sid);
$section = $newDB->getOne('Section');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $employees = $_POST['employees'];
  foreach($employees as $k=>$e){
    $newDB->where('EmployeesId', $e);
    $newDB->update('employees',['SectionId' => intval($sid)]);
  }
  
} else {
  //echo 'NA';
}
$newDB->where('SectionId', $sid);
$c_empls = $newDB->get('employees');

$newDB->where('SectionId', null,'IS');
$employees = $newDB->get('employees');


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
        <div class="container-fluid">

        <form action="assign_section.php?sid=<?php echo $sid ?>" method="Post">
          <div class="form-group row">
            <h3>Section: <?php echo $section['SectionName']?></strong></h3>
          </div>
          <div class="form-group row">
          <p class="mb-0">All users in section(<?php echo count($c_empls)?>)</p>
          <select class="form-control">
            <?php 
            foreach ($c_empls as $key => $value) {
              echo "<option value='".$value['EmployeesId']."'>".$value['EmployeesCode'].'-'.$value['EmployeesName']."</option>";
            }
            ?>            
          </select>
          </div>
          <div class="form-group row">
            <p class="mb-0">Add employees to section</p>
          <select id="employees_list" class="form-control" required name="employees[]" multiple="multiple">
            <?php 
            echo "<option value=''>select employees</option>";
            foreach ($employees as $key => $value) {
              echo "<option value='".$value['EmployeesId']."'>".$value['EmployeesCode'].'-'.$value['EmployeesName']."</option>";
            }
            ?>            
          </select>
          </div>
          <div class="form-group row">
            <button type="submit" class='btn btn-success form-control'>Submit</button>
          </div>
        </form>


        </div>
        <!-- /.container-fluid -->

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
    $(function () {
      $('#employees_list').select2();
    });
  </script>

</body>

</html>

