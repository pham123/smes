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
        <h1>REPORT HERE</h1>

        <?php
        $sql = "SELECT prd.ProductsName,prd.ProductsNumber,prd.ProductsUnit, scm.SupplyChainObjectShortName as FromName, scm2.SupplyChainObjectShortName as ToName,sto.*,stoi.*
        FROM `stockoutputitems` stoi 
        INNER JOIN stockoutputs sto on sto.StockOutputsId = stoi.StockOutputsId
        INNER JOIN supplychainobject scm on sto.FromId = scm.SupplyChainObjectId
        INNER JOIN supplychainobject scm2 on sto.ToId = scm2.SupplyChainObjectId
        INNER JOIN products prd on prd.ProductsId = stoi.ProductsId
        ORDER BY StockOutPutsDate DESC;
        ";
        $result = $oDB->fetchAll($sql);
        ?>

<div class="table-responsive">
        <?php
        echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
        echo "<thead>";
        echo "<tr>";
            echo "<th>".$oDB->lang('Index')."</th>";
            echo "<th>".$oDB->lang('Date')."</th>";
            echo "<th>".$oDB->lang('No')."</th>";
            echo "<th>".$oDB->lang('ProductName')."</th>";
            echo "<th>".$oDB->lang('ProductNumber')."</th>";
            echo "<th>".$oDB->lang('Quantity')."</th>";
            echo "<th>".$oDB->lang('Unit')."</th>";
            echo "<th>".$oDB->lang('From')."</th>";
            echo "<th>".$oDB->lang('To')."</th>";
            echo "<th>".$oDB->lang('Type')."</th>";
            echo "<th>".$oDB->lang('Remark')."</th>";
        echo "</tr>";
        echo "</thead>";


        echo "<tbody>";

        foreach ($result as $key => $value) {
            echo "<tr>";
            echo "<td>".($key+1)."</td>";
            echo "<td>".$value['StockOutputsDate']."</td>";
            echo "<td>".$value['StockOutputsNo']."</td>";
            echo "<td>".$value['ProductsName']."</td>";
            echo "<td>".$value['ProductsNumber']."</td>";
            echo "<td>".$value['StockOutputItemsQty']."</td>";
            echo "<td>".$value['ProductsUnit']."</td>";
            echo "<td>".$value['FromName']."</td>";
            echo "<td>".$value['ToName']."</td>";
            echo "<td>".$value['StockOutputsType']."</td>";
            echo "<td>".$value['StockOutputItemsRemark']."</td>";
            echo "</tr>";
        }

        echo "</tbody>";

        echo "</table>";

          ?>
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
