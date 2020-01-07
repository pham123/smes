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
          $table_header  = 'ProductsNumber,ProductsName,ProductsDescription,CategoriesName,Month,Ton';
          
          $newDb = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
          $newDb->join("Products p", "es.ProductsId=p.ProductsId", "LEFT");
          $newDb->join("Categories c", "p.ProductsCategory=c.CategoriesId", "LEFT");
          $newDb->orderBy('ExistSparePartsId', 'desc');
          $table_data = $newDb->get ("ExistSpareParts es", null, "p.ProductsNumber,p.ProductsName,p.ProductsDescription,c.CategoriesName,es.ExistSparePartsMonth as Month,es.ExistSparePartsQty as Ton");
          // $table_link = "editsparepart.php?id=";
          ?>

        <div class="table-responsive">
          <h3>Spare part tồn theo tháng</h3>
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
              <td>Month:</td>
              <td><input type="text" class="form-control" id="month_filter"></td>
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

                      echo "<td>".$value[$value2]."</td>";
                
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
  <script src="../js/datepicker.min.js"></script>
  <link href="../css/datepicker.min.css" rel="stylesheet"/>

  <script>
    $.fn.dataTable.ext.search.push(
      function( settings, data, dataIndex ) {
          let ct_filter = $('#category_filter').val();
          let month_filter = $('#month_filter').val();
          let ct_value = data[3];
          let month_value = data[4];

          return ct_value.includes(ct_filter) && month_value.includes(month_filter);
      }
    );
    $(function () {
      $('#month_filter').datepicker({
        format: 'yyyy-mm'
      });
      var data_table = $('#dataTable').DataTable();
      $('#category_filter').change(function(){
        data_table.draw();
      });
      $('#month_filter').change(function(){
        data_table.draw();
      });

      $('selectpicker').selectpicker();
    });
  </script>

</body>

</html>
