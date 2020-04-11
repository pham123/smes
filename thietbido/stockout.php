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
$page_heading = 'Import history';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();

$table_header  = 'id,Từ,Đến,No,Ngày,Kho,Time,Print';
//using new db library
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
$newDB->join("SupplyChainObject sco1", "sco1.SupplyChainObjectId=s.FromId", "LEFT");
$newDB->join("SupplyChainObject sco2", "sco2.SupplyChainObjectId=s.ToId", "LEFT");
$newDB->where('StockOutputsStatus', 0, '!=');
$newDB->where('StockOutputsModule', 'inout');
$newDB->where('UsersId', $_SESSION[_site_]['userid']);
$newDB->orderBy('s.StockOutputsDate', 'DESC');
$table_data = $newDB->get ("StockOutputs s", null, "s.StockOutputsId as id,sco1.SupplyChainObjectName Từ,sco2.SupplyChainObjectName Đến,s.StockOutputsNo No,s.StockOutputsDate Ngày,s.StockOutputsType Kho,s.StockOutputsTime as Time,CONCAT('<a href=\"print-Stockout.php&quest;id=',s.StockOutputsId,'\" target=\"_blank\" >','<i class=\"fas fa-print\"></i>', '</a>') as Print");
$table_link = "updateStockOutput.php?id=";
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
              
            <div class="table-responsive">
            <h5 class="text-primary">DANH SÁCH HÀNG XUẤT</h5>
                <table border="0" cellspacing="5" cellpadding="5" class="display nowrap">
                  <tbody>
                    <tr>
                      <td>Ngày:</td>
                      <td><input type="date" id="date_filter"></td>
                    </tr>
                  </tbody>
                </table>
                <?php include('../views/template_table.php') ?>
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

  <?php require('../views/template-footer-order.php'); ?>

  <script>
    $.fn.dataTable.ext.search.push(
      function( settings, data, dataIndex ) {
          let date_filter = $('#date_filter').val();
          let date_value = data[4];
          if(date_filter){
            if ( date_filter == date_value )
            {
                return true;
            }else{
              return false;
            }
          }
          return true;
      }
    );
    $(function () {
      $('selectpicker').selectpicker();
      var data_table = $('#dataTable').DataTable( {
          dom: "<'row'<'col-md-10 pull-left'f><'col-md-2 pull-right'B>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
          buttons: [
              // 'copy', 'csv', 'excel', 'pdf', 'print'
              'excel','copy'
          ],
          language: {
              search: "",
              searchPlaceholder: "Search..."
          },
          order: [[0, "desc"]]
          //"paging": false
      } );
      $('#date_filter').change(function(){
        data_table.draw();
      });
    });
  </script>

</body>

</html>
