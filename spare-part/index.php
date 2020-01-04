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
$page_css = 'table th,table td{font-size: 15px;}';
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
          $table_header  = 'ProductsNumber,ProductsName,ProductsEngName,ProductsLocation,ProductsDescription,ProductsUnit,ProductsStock,ProductsSafetyStk,CategoriesName';
          
          $newDb = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
          $newDb->where('p.ProductsOption', 4);
          $newDb->join("Categories c", "p.ProductsCategory=c.CategoriesId", "LEFT");
          $table_data = $newDb->get ("Products p", null, "p.ProductsId as id,p.ProductsNumber,p.ProductsName,p.ProductsEngName,p.ProductsLocation,p.ProductsDescription,p.ProductsUnit,p.ProductsStock,p.ProductsSafetyStk,c.CategoriesName");
          $table_link = "editsparepart.php?id=";
          ?>

        <div class="table-responsive">
        <table border="0" cellspacing="5" cellpadding="5" class="display nowrap">
          <tbody><tr>
              <td>Category:</td>
              <td>
              <select id="category_filter" class="form-control" required>
                <?php 
                $ctes = $oDB->sl_all('categories',1);
                echo "<option value=''>all category</option>";
                foreach ($ctes as $key => $value) {
                  echo "<option value='".$value['CategoriesName']."'>".$value['CategoriesName']."</option>";
                }
                ?>
                
              </select>
              </td>
              <td>Status:</td>
              <td><select class="form-control" id="status_filter"><option value="0">All spare part</option><option value="1">Lower safety spare part</option></td>
          </tr>
        </tbody>
      </table>
        <?php
          $tablearr = explode(',',$table_header);
          echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
          echo "<thead>";
          echo "<tr>";
          foreach ($tablearr as $key => $value) {
              echo "<th>".$oDB->lang($value)."</th>";
          }
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";
          foreach ($table_data as $key => $value) {

              echo "<tr>";
              foreach ($tablearr as $key2 => $value2) {
                  if ($key2==0) {
                          echo "<td><a href='".$table_link.$value['id']."'>".$value[$value2]."</a></td>";
                  }else{
                    if($key2 == 5){
                      if($value['ProductsStock'] < $value['ProductsSafetyStk'] ){
                        echo "<td class='bg-danger text-white'>".$value[$value2]."</td>";
                      }else{
                        echo "<td class=''>".$value[$value2]."</td>";
                      }
                    }else{
                      echo "<td>".$value[$value2]."</td>";
                    }
                  }
                
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

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script> 
  <!-- Latest compiled and minified JavaScript -->
  <script src="../vendor/select/dist/js/bootstrap-select.min.js"></script>
  <!-- (Optional) Latest compiled and minified JavaScript translation files -->
  <script src="../vendor/select/dist/js/i18n/defaults-*.min.js"></script>

  <script>
    $.fn.dataTable.ext.search.push(
      function( settings, data, dataIndex ) {
          let ct_filter = $('#category_filter').val();
          let status = $('#status_filter').val();
          let ct_value = data[8];
          let stockValue = data[6];
          let safeValue = data[7];
          if(status == '0'){
            if(ct_filter == '')
            {
              return true;
            }
            if ( ct_filter == ct_value )
            {
                return true;
            }
          }
          if(status == '1'){
            if(ct_filter == '' && stockValue < safeValue)
            {
              return true;
            }
            if ( ct_filter == ct_value && stockValue < safeValue)
            {
                return true;
            }
          }
          return false;
      }
    );
    $(function () {
      var data_table = $('#dataTable').DataTable();
      $('#category_filter').change(function(){
        data_table.draw();
      });
      $('#status_filter').change(function(){
        data_table.draw();
      });

      $('selectpicker').selectpicker();
    });
  </script>

</body>

</html>
