<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$pagetitle = $user->module;
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
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
        <?php 
          $table_header  = 'IssueDate,Object,PartName,ProductionDate,Title,Picture,Lot Qty,NgQty,NgRate,Schedule,Pic,Status,Edit';
          
          $sql = "SELECT 
          qi.QualityIssuelistId,
          qi.QualityIssuelistDate,
          qi.QualityIssuelistProductionDate,
          qi.QualityIssuelistTitle,
          qi.QualityIssuelistLotNo,
          qi.QualityIssuelistLotQuantity,
          qi.QualityIssuelistNgQuantity,
          qi.QualityIssuelistDueDate,
          qi.UsersId,
          qi.QualityIssuelistCreator,
          qi.QualityIssuelistStatus,
          qi.QualityIssuelistDueDate,
          pr.ProductsName,
          pr.ProductsNumber,
          urs.UsersFullName,
          urs.UsersName,
          scm.SupplyChainObjectName
           from QualityIssuelist qi
           inner join Products pr on pr.ProductsId = qi.ProductsId
           inner join Users urs on urs.UsersId = qi.UsersId
           inner join SupplyChainObject scm on scm.SupplyChainObjectId = qi.SupplyChainObjectId
          Order by qi.QualityIssuelistId DESC LIMIT 50
          ";

          $issuelist = $oDB->fetchAll($sql);
          //var_dump($issuelist);
        ?>
        <div class="table-responsive">
        <?php
        $tablearr = explode(',',$table_header);
        echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
        echo "<thead>";
        echo "<tr>";
        foreach ($tablearr as $key => $value) {
            echo "<th>".$oDB->lang($value)."</th>";
        }
        if(isset($product_picture)){
            echo "<th>Product Picture</th>";
        }
        echo "</tr>";
        echo "</thead>";
        // echo "<tfoot>";
        // echo  "<tr>";
        // foreach ($tablearr as $key => $value) {
        //     echo "<th>".$lang[$value]."</th>";
        // }
        // echo  "</tr>";
        // echo "</tfoot>";
        echo "<tbody>";
        foreach ($issuelist as $key => $value) {
          echo "<tr>";
          echo "<td><p>".date("d-M",strtotime($value['QualityIssuelistDate']))."</p><p>".date("Y",strtotime($value['QualityIssuelistDate']))."</p></td>";
          echo "<td>".$value['SupplyChainObjectName']."</td>";
          echo "<td><p>".$value['ProductsNumber']."</p><p>".$value['ProductsName']."</p></td>";
          echo "<td><p>".date("d-M",strtotime($value['QualityIssuelistProductionDate']))."</p><p>".date("Y",strtotime($value['QualityIssuelistProductionDate']))."</p></td>";
          echo "<td>".$value['QualityIssuelistTitle']."</td>";
          echo "<td><img src='image/small/img_".$value['QualityIssuelistId'].".jpg' style='width:100px' alt=''></td>";
          echo "<td>".$value['QualityIssuelistLotQuantity']."</td>";
          echo "<td>".$value['QualityIssuelistNgQuantity']."</td>";
          $rate = 100*round($value['QualityIssuelistNgQuantity']/$value['QualityIssuelistLotQuantity'],2);
          echo "<td>".$rate."%</td>";
          echo "<td><p>".date("d-M",strtotime($value['QualityIssuelistDueDate']))."</p><p>".date("Y",strtotime($value['QualityIssuelistDueDate']))."</p></td>"; 
          echo "<td>".$value['UsersName']."</td>";

          $status = color($value['QualityIssuelistStatus']);
          echo "<td style='background-color:".$status[0]."'>".$oDB->lang($status[1])."</td>";
          if ($user->acess()==1||$user->acess()==2||$_SESSION[_site_]['userid']==$value['UsersId']) {
            echo "<td><a href='editissue.php?id=".$value['QualityIssuelistId']."'><i class='fas fa-edit'></i></a></td>";
          }else{
            echo "<td><i class='fas fa-edit'></i></td>";
          }
          
          echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        ?>
        </div>
        
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
