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
          $sql = "SELECT scm.SupplyChainObjectName,scm.SupplyChainObjectId,  
          count(*) as Total,
          SUM(case when MEInforStatus = 1 then 1 else 0 end) as TotalOk,
          SUM(case when MEInforStatus = 2 then 1 else 0 end) as TotalSpare,
          SUM(case when MEInforStatus = 3 then 1 else 0 end) as TotalBroken,
          SUM(case when MEInforStatus = 4 then 1 else 0 end) as TotalLost,
          SUM(case when MEInforStatus = 5 then 1 else 0 end) as TotalCal,
          SUM(case when MEInforStatus <> 3 AND MEInforStatus <> 4 AND date(`MEInforNextCalDate`) <= (CURDATE() + INTERVAL 45 DAY) then 1 else 0 end) as Total45,
          SUM(case when MEInforStatus <> 3 AND MEInforStatus <> 4 AND date(`MEInforNextCalDate`) <= (CURDATE() + INTERVAL 15 DAY) then 1 else 0 end) as Total15
          FROM `MEInfor`
          INNER JOIN Products prd ON prd.ProductsId = MEInfor.ProductsId
          INNER JOIN Users ON Users.UsersId = MEInfor.UsersId
          INNER JOIN SupplyChainObject scm on scm.SupplyChainObjectId = MEInfor.SupplyChainObjectId
          WHERE MEInforId in (SELECT MAX(`MEInforId`) as id FROM `MEInfor` GROUP BY ProductsId )
          GROUP BY scm.SupplyChainObjectName,scm.SupplyChainObjectId";
          $result = $oDB->fetchAll($sql);
          // echo "<pre>";
          // var_dump($result);
          // echo "</pre>";

          // exit();
          ?>

        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
            <th>Bộ phận</th>
            <th>Tổng</th>
            <th>Đang sử dụng</th>
            <th>Dự phòng</th>
            <th>Hỏng</th>
            <th>Mất</th>
            <th>Hiệu chuẩn</th>
            <th>Ngày hiệu chuẩn - 15</th>
            <th>Ngày hiệu chuẩn - 45</th>
            </tr>
          </thead>
          <tbody>
          
            <?php
              foreach ($result as $key => $value) {
                echo "<tr>";
                echo  "<td>".$value['SupplyChainObjectName']."</td>";
                echo  "<td>".$value['Total']."</td>";
                echo  "<td>".$value['TotalOk']."</td>";
                echo  "<td>".$value['TotalSpare']."</td>";
                echo  "<td>".$value['TotalBroken']."</td>";
                echo  "<td>".$value['TotalLost']."</td>";
                echo  "<td>".$value['TotalCal']."</td>";
                echo  "<td style='background-color:red;'><a href='list15.php?tic=".$value['SupplyChainObjectId']."'>".$value['Total15']."</a></td>";
                echo  "<td style='background-color:yellow;'><a href='list45.php?tic=".$value['SupplyChainObjectId']."'>".($value['Total45']-$value['Total15'])."</a></td>";
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
