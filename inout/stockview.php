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

$kho = (isset($_GET['SupplyChainObjectId'])) ? safe($_GET['SupplyChainObjectId']) : '42';
$materialtype = 9;
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
        <h1>Stock view here</h1>

        
          <form action="">
          <div class="row">
            <div class="col-md-4">
              <?php makedroplist('SupplyChainObject','SupplyChainTypeId = 2',42,$width='100%') ?>
            </div>
            <div class="col-md-4">
              <button type="submit" class="form-control">Submit</button>
            </div>
          </div>
          </form>
        

        <?php
        $sql = "Select prd.ProductsId, prd.ProductsName, prd.ProductsNumber , SUM(StockInputItemsQty) as totalin 
        from StockInputItems sii
        inner join StockInputs si on si.StockInputsId = sii.StockInputsId AND si.ToId = ".$kho."
        Inner Join Products prd on prd.ProductsId = sii.ProductsId
        Inner Join MaterialTypes mt on mt.MaterialTypesId = prd.MaterialTypesId AND  prd.MaterialTypesId = ".$materialtype."
        Group By prd.ProductsId, prd.ProductsName, prd.ProductsNumber;
        ";
        $result = $oDB->fetchAll($sql);

        $sql = "select prd.ProductsId, SUM(StockOutputItemsQty) as totalout from StockOutputItems sii
        inner join StockOutputs si on si.StockOutputsId = sii.StockOutputsId AND si.FromId = ".$kho."
        inner join Products prd on prd.ProductsId = sii.ProductsId
        inner join MaterialTypes mt on mt.MaterialTypesId = prd.MaterialTypesId AND  prd.MaterialTypesId = ".$materialtype."
        Group by prd.ProductsId";

        $totalout = $oDB->fetchAll($sql);
        $outarr = array();
        foreach ($totalout as $key => $value) {
          $outarr[$value['ProductsId']] = $value['totalout'];
        }

        ?>

<div class="table-responsive">
        <?php
        echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
        echo "<thead>";
        echo "<tr>";
            echo "<th>".$oDB->lang('Index')."</th>";
            echo "<th>".$oDB->lang('ProductNumber')."</th>";
            echo "<th>".$oDB->lang('ProductName')."</th>";
            echo "<th>".$oDB->lang('stock')."</th>";
        echo "</tr>";
        echo "</thead>";


        echo "<tbody>";

        foreach ($result as $key => $value) {
            echo "<tr>";
            echo "<td>".($key+1)."</td>";
            echo "<td>".$value['ProductsNumber']."</td>";
            echo "<td>".$value['ProductsName']."</td>";
            $retVal = (isset($outarr[$value['ProductsId']])) ? $outarr[$value['ProductsId']] : 0 ;
            echo "<td>".($value['totalin']-$retVal)."</td>";
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
