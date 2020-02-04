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

//current module
$newDB->where('ModulesName', 'memos');
$module = $newDB->getOne('modules');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(isset($_POST['uids'])){
        $uids = $_POST['uids'];
        foreach($uids as $key => $uid){
        $newDB->insert('access', ['UsersId' => $uid, 'ModulesId' => $module['ModulesId'], 'AccessOption' => 3]);
        }
  }
 
} else {
  //echo 'NA';
}

//all users have permission to memos modules
$newDB->where('ModulesId', $module['ModulesId']);
$users_have_permission = $newDB->get('access', null, 'UsersId');
$uids_have_permission = [];
foreach($users_have_permission as $key => $value){
    $uids_have_permission[] = $value['UsersId'];
}


// $newDB->where('a.ModulesId', $module['ModulesId']);
$newDB->where('UsersId', $uids_have_permission, 'NOT IN');
$users = $newDB->get("users u", null, "u.UsersId,u.UsersName,u.UsersFullName");

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

        <form action="manage_permission.php" method="Post">
          <div>
            <p class="mb-0">All users not have permission</p>  
          <table class="table table-striped table-bordered" id="dataTable">
              <thead>
              <tr class="bg-secondary text-white">
                <th>#</th>
                <th>user</th>
                <th>name</th>
                <th>permission</th>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach($users as $key => $u)
            {
            ?>
              <tr><td><?php echo ++$key ?></td><td><?php echo $u['UsersName'] ?></td><td><?php echo $u['UsersFullName'] ?></td><td><input type="checkbox" name='uids[]' value="<?php echo $u['UsersId']?>"> allow access</td></tr>
            <?php
            }
            ?>
            </tbody>
            </table>
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
      $('selectpicker').selectpicker();
    });
  </script>

</body>

</html>

