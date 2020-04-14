<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/sdb.php');
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

        <div class="row">
        

        <div class="col-md-8">
          <?php 
          $sql = "SELECT MEInfor.*, prd.ProductsName, prd.ProductsNumber
          FROM `MEInfor`
          INNER JOIN Products prd ON prd.ProductsId = MEInfor.ProductsId
          WHERE MEInfor.ProductsId =?
          ORDER BY MEInforId DESC LIMIT 1";
          if (isset($_GET['id'])) {
            $result = $sDB->query($sql,$_GET['id'])->fetchArray();
          }else{
            exit();
          }
          $readonly = ($user->acess()==1) ? '' : 'Readonly' ;
          ?>
        <form action="listen_update.php" method="post">
        <input type="hidden" name="ProductsId" value="<?php echo $result['ProductsId'] ?>">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <tr><th>Mã thiết bị</th> <td><?php echo $result['ProductsNumber'] ?></td></tr>
          <tr><th>Tên thiết bị</th> <td><?php echo $result['ProductsName'] ?></td></tr>
          <tr><th>Latest Calibration No.</th><td><input type="text" name="MEInforCalibrationNo" class='form-control' id="" value='<?php echo $result['MEInforCalibrationNo'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Serial No.</th><td><input type="text" name="MEInforSN" id="" class='form-control' value='<?php echo $result['MEInforSN'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Model</th><td><input type="text" name="MEInforModel" id="" class='form-control' value='<?php echo $result['MEInforModel'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Minimum indication</th><td><input type="text" name="MEInforMinimum" class='form-control' id="" value='<?php echo $result['MEInforMinimum'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Specification</th><td><input type="text" name="MEInforSpec" class='form-control' id="" value='<?php echo $result['MEInforSpec'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Maker</th><td><input type="text" name="MEInforMaker" id="" class='form-control' value='<?php echo $result['MEInforMaker'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Buy by VN/Korea</th><td><input type="text" name="MEInforMakerLocation" id="" class='form-control' value='<?php echo $result['MEInforMakerLocation'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Received date</th><td><input type="date" name="MEInforReceivedDate" id="" class='form-control' value='<?php echo $result['MEInforReceivedDate'] ?>' <?php echo $readonly ?>></td></tr>

          <tr><th>TIC</th><td>
          <?php
          if ($user->acess()==1) {
            makedroplistreadonly('SupplyChainObject','SupplyChainTypeId=2',$result['SupplyChainObjectId'],$readonly);
          }else{
            echo "<input type='hidden' name='SupplyChainObjectId' value='".$result['SupplyChainObjectId']."'>";
          }
          ?>
          
          </td>
          </tr>
          <!-- makedroplistreadonly -->
          <tr><th>Location</th><td><input type="text" name="MEInforLocation" id="" class='form-control' value='<?php echo $result['MEInforLocation'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>PIC</th><td>
          <?php
          if ($user->acess()==1) {
            makedroplistreadonly('Users',1,$result['UsersId'],$readonly);
          }else{
            echo "<input type='hidden' name='UsersId' value='".$result['UsersId']."'>";
          }
          ?>
          </td></tr>
          <tr><th>Day (start using)</th><td><input type="date" name="MEInforStartDate" id="" class='form-control' value='<?php echo $result['MEInforStartDate'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Latest Calibration date</th><td><input type="date" name="MEInforLastCalDate" id="" class='form-control' value='<?php echo $result['MEInforLastCalDate'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Next calibration schedule</th><td><input type="date" name="MEInforNextCalDate" id="" class='form-control' value='<?php echo $result['MEInforNextCalDate'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Calibration Place</th><td><input type="text" name="MEInforCalLocation" id="" class='form-control' value='<?php echo $result['MEInforCalLocation'] ?>' <?php echo $readonly ?>></td></tr>
          <tr><th>Status</th><td>
          <?php 
          $array = array(
            array('Id' => '1' , 'Name' => 'Using'),
            array('Id' => '2' , 'Name' => 'Spare'),
            array('Id' => '3' , 'Name' => 'Broken'),
            array('Id' => '4' , 'Name' => 'Lost'),
            array('Id' => '5' , 'Name' => 'Calibration'),
          );
          droplistfromarr('MEInforStatus',$array,$result['MEInforStatus']);
          ?>
          </td></tr>
          <tr><th>Remark</th><td><input type="text" name="MEInforRemark" id="" class='form-control' value='' required></td></tr>
        </table>
        <button type="submit" class='form-control'>Update</button>
        </form>
        </div>

        <div class="col-md-4">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <tr>
          <?php
          if (file_exists("../products/image/img_".$result['ProductsId'].".jpg")) {
           ?>
              <td><img src="../products/image/img_<?php echo $result['ProductsId'] ?>.jpg?<?php filemtime("../products/image/img_".$result['ProductsId'].".jpg"); ?>" alt="" width="100%"></td>
           <?php
          }else{
            ?>
              <td></td>
            <?php

          }
          ?>
          </tr>
          <form action="listen-update-picture.php?id=<?php echo $result['ProductsId'] ?>" method="post" enctype="multipart/form-data">
          <tr>
            <td><input type="file" name="file" id="" accept="image/x-png,image/gif,image/jpeg"></td>
          </tr>
          <tr>
            <td><button type="submit">Update Picture</button></td>
          </tr>
          </form>
        </table>
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
