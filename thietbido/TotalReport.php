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
          <?php 
          $sql = "SELECT `meinfor`.*, prd.ProductsName, prd.ProductsNumber 
          FROM `meinfor`
          INNER JOIN Products prd ON prd.ProductsId = meinfor.ProductsId AND prd.MaterialTypesId = 6
          WHERE MEInforId in (SELECT MAX(`MEInforId`) as id FROM `meinfor` GROUP BY ProductsId )";
          $result = $oDB->fetchAll($sql);
          // echo "<pre>";
          // var_dump($result);
          // echo "</pre>";
          ?>

        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
            <th>Equipment No.</th>
            <th>Equipment name</th>
            <th>Latest Calibration No.</th>
            <th>Serial No.</th>
            <th>Model</th>
            <th>Minimum indication</th>
            <th>Specification</th>
            <th>Maker</th>
            <th>Buy by VN/Korea</th>
            <th>Received date</th>
            <th>TIC</th>
            <th>Location</th>
            <th>PIC</th>
            <th>Day (start using)</th>
            <th>Latest Calibration date</th>
            <th>Next calibration schedule</th>
            <th>Calibration Place</th>
            <th>Status</th>
            <th>Remark</th>
            </tr>
          </thead>
          <tbody>
          
            <?php
              foreach ($result as $key => $value) {
                echo "<tr>";

                echo  "<td>".$value['ProductsNumber']."</td>";
                echo  "<td>".$value['ProductsName']."</td>";
                
                echo  "<td>".$value['MEInforCalibrationNo']."</td>";
                echo  "<td>".$value['MEInforSN']."</td>";
                echo  "<td>".$value['MEInforModel']."</td>";
                echo  "<td>".$value['MEInforMinimum']."</td>";
                echo  "<td>".$value['MEInforSpec']."</td>";
                echo  "<td>".$value['MEInforMaker']."</td>";
                echo  "<td>".$value['MEInforMakerLocation']."</td>";

                echo  "<td>".$value['MEInforReceivedDate']."</td>";
                echo  "<td>".$value['SupplyChainObjectId']."</td>";

                echo  "<td>".$value['MEInforLocation']."</td>";

                echo  "<td>".$value['UsersId']."</td>";

                echo  "<td>".$value['MEInforStartDate']."</td>";
                echo  "<td>".$value['MEInforLastCalDate']."</td>";
                echo  "<td>".$value['MEInforNextCalDate']."</td>";

                echo  "<td>".$value['MEInforCalLocation']."</td>";
                echo  "<td>".$value['MEInforStatus']."</td>";
                echo  "<td>".$value['MEInforStatus']."</td>";

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
