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

$uid = $_GET['uid'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //update employee
  $newDB->where('UsersId', $uid);
  $newDB->update('users', ['EmployeesId' => $_POST['EmployeesId']]);

  //update role
  $mids = $_POST['mids'];
  $mvalues = $_POST['mvalues'];
  foreach($mids as $key => $mid){
    $newDB->where('UsersId', $uid);
    $newDB->where('ModulesId', $mid);
    $a = $newDB->getOne('access');

    if($mvalues[$key] == 'NA'){
      if($a){
        $newDB->where('UsersId', $uid);
        $newDB->where('ModulesId', $mid);
        $newDB->delete('access');
      }else{

      }
    }else{
      if($a){
        $newDB->where('UsersId', $uid);
        $newDB->where('ModulesId', $mid);
        $newDB->update('access', ['AccessOption' => $mvalues[$key]]);
      }else{
        $newDB->insert('access', ['ModulesId' => $mid, 'UsersId' => $uid, 'AccessOption' => $mvalues[$key], 'AccessCreateDate' => date("Y-m-d H:i:s"), 'AccessUpdateDate' => date("Y-m-d H:i:s")]);
      }
    }
  }
 
} else {
  //echo 'NA';
}

$user= $newDB->where('UsersId', $uid)->getOne('Users');
$modules = $newDB->get('modules',null,'ModulesId,ModulesName');
$access = $newDB->get('access', null, 'ModulesId,UsersId');

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
        <form action="manage_user_module.php?uid=<?php echo $uid ?>" method="Post">
          <div class="form-group row">
            Employee:&nbsp;
            <select required class="form-control selectpicker" data-style="btn-secondary" data-live-search='true' name="EmployeesId">
            <?php
            echo "<option value=''>select employee</option>";
            foreach ($employees as $key => $value) {
              if($user['EmployeesId'] == $value['EmployeesId']){
                echo "<option selected value='".$value['EmployeesId']."'>".$value['EmployeesCode'].'-'.$value['EmployeesName']."</option>";
              }else{
                echo "<option value='".$value['EmployeesId']."'>".$value['EmployeesCode'].'-'.$value['EmployeesName']."</option>";
              }
            }
            ?>            
            </select>
          </div>
        <div class="form-group row">
            <p class="mb-0">Config module for <strong><?php echo $user['UsersName'] ?></strong></p>  
          <table class="table table-sm table-striped table-bordered">
              <tr class="bg-secondary text-white">
                <th>#</th>
                <th>Module</th>
                <th>Value</th>
              </tr>
            <?php
            foreach($modules as $key => $m)
            {
            ?>
              <tr><td><?php echo ++$key ?></td><td><?php echo $m['ModulesName'] ?><input type="hidden" name="mids[]" value="<?php echo $m['ModulesId']?>"></td><td><select class="form-control" name="mvalues[]">
                <?php
                  if(in_array(['ModulesId' => $m['ModulesId'], 'UsersId' => intval($uid)], $access)){
                    $newDB->where('UsersId', $uid);
                    $newDB->where('ModulesId', $m['ModulesId']);
                    $option = $newDB->getOne('access','AccessOption')['AccessOption'];
                    echo "<option value='NA'>NA</option>";
                    echo "<option value='1' ".($option==1?'selected':'').">1</option>";
                    echo "<option value='2' ".($option==2?'selected':'').">2</option>";
                    echo "<option value='3' ".($option==3?'selected':'').">3</option>";
                    echo "<option value='4' ".($option==4?'selected':'').">4</option>";
                  }else{
                    echo "<option value='NA' selected>NA</option>";
                    echo "<option value='1'>1</option>";
                    echo "<option value='2'>2</option>";
                    echo "<option value='3'>3</option>";
                    echo "<option value='4'>4</option>";
                  }
                ?>
              </select></td></tr>
            <?php
            }
            ?>
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

