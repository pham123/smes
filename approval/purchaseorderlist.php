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

$table_header  = 'PONo,PODate,Supplier,Shipment,Print,Payment';
//using new db library
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
$newDB->join('supplychainobject spl', 'spl.SupplyChainObjectId=po.SupplyChainObjectId', 'left');
$newDB->where('PurchaseOrdersStatus', 1);
$newDB->where('UsersId', $_SESSION[_site_]['userid']);
$newDB->orderBy('po.PurchaseOrdersDate', 'DESC');
$table_data = $newDB->get ("PurchaseOrders po", null, "po.PurchaseOrdersId,CONCAT('<a href=\"attach-scan-po.php&quest;id=',po.PurchaseOrdersId,'\" >',po.PurchaseOrdersNo, '</a>') as PONo,po.PurchaseOrdersDate as PODate,spl.SupplyChainObjectName as Supplier, po.PurchaseOrdersShipmentMethod as Shipment,CONCAT('<a href=\"print-po.php&quest;id=',po.PurchasesId,'\" target=\"_blank\" >','<i class=\"fas fa-print\"></i>', '</a>') as Print,if(char_length(po.PurchaseOrdersFileName)>0, CONCAT('<a href=\"#\" target=\"_blank\">', 'Make payment', '</a>'), 'Not have scan file') as Payment");
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
                <h3 class="text-center">PURCHASE ORDERS</h3>
                <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
                  <thead>
                      <tr>
                        <th>PO No</th>
                        <th>PO Date</th>
                        <th>Supplier</th>
                        <th>Shipment</th>
                        <th>PO File</th>
                        <th>Print</th>
                        <th>Payment</th>
                      </tr>
                  </thead>

                  <tbody>
                      <?php
                        foreach ($table_data as $key => $value) {
                          $filename = $newDB->where('PurchaseOrdersId', $value['PurchaseOrdersId'])->getOne('purchaseorders')['PurchaseOrdersFileName'];
                          if(file_exists('po/'.$value['PurchaseOrdersId'].'_'.$filename)){
                            $havePoFile = true;
                          }else{
                            $havePoFile = false;
                          }
                          
                          echo '<tr><td><a href="attach-scan-po.php?id='.$value['PurchaseOrdersId'].'">'.$value['PONo'].'</a></td>';
                          echo '<td>'.$value['PODate'].'</td>';
                          echo '<td>'.$value['Supplier'].'</td>';
                          echo '<td>'.$value['Shipment'].'</td>';
                          if($havePoFile){
                            echo '<td><a target="_blank" href="po/'.$value['PurchaseOrdersId'].'_'.$filename.'">view</a></td>';
                          }else{
                            echo '<td class="text-danger">no po file</td>';
                          }
                          echo '<td><a href="print-po.php?id='.$value['PurchaseOrdersId'].'" target="_blank"><i class="fas fa-print"></i></a></td>';
                          if(!$havePoFile){
                            echo '<td class="text-danger">no po file</td></tr>';
                          }else{
                            echo '<td><a href="#?id='.$value['PurchaseOrdersId'].'">Make payment</a></td></tr>';
                          }
                        }
                      ?>

                  </tbody>
          
                </table>
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
    $(function () {
      $('selectpicker').selectpicker();
      $('#dataTable').DataTable( {
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
    });
  </script>

</body>

</html>
