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
require('../views/template-header.php');
require('../function/template.php');

$table_header  = 'BomsPartNo,BomsPartName,BomsSize,BomsNet,BomsGloss,BomsMaterial,BomsUnit,BomsQty,BomsProcess,BomsMaker,BomsClassifiedMaterial,BomsMachine';

//using new db library
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
$newDB->join("products p", "b.ProductsId=p.ProductsId", "LEFT");
$newDB->join("processes pro", "b.ProcessesId=pro.ProcessesId", "LEFT");
$newDB->join("makers m", "b.MakersId=m.MakersId", "LEFT");
$newDB->join("classifiedmaterials c", "b.ClassifiedMaterialsId=c.ClassifiedMaterialsId", "LEFT");
$newDB->join("machines ma", "b.MachinesId=ma.MachinesId", "LEFT");
$newDB->where("b.BomlistsId", $_GET['id']);
$table_data = $newDB->get ("boms b", null, "p.ProductsNumber,p.ProductsName,p.ProductsSize,p.ProductsNet,p.ProductsGloss,p.ProductsMaterial,p.ProductsUnit,b.BomsId,b.BomsQty,b.BomsParentId,b.BomsPath,pro.ProcessesName,m.MakersName,c.ClassifiedMaterialsName,ma.MachinesName");
$table_link = "editbom.php?id=";

$heading_title = 'BOM';
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

        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive" style="max-width: 100%; over-flow: none;">
            <h4><?php echo strtoupper($table_data[0]['ProductsNumber'])?></h4>
          <?php 
          // include('../views/template_table.php') 
          include('bom_table.php') 
          ?>
            </div>
          </div>
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
