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

$table_header  = 'PurchasesNo,PurchasesDate,RequestSection,ReceiveSection,Urgent,Print,PO';
//using new db library
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
$newDB->join("Section s", "s.SectionId=p.RequestSectionId", "LEFT");
$newDB->where('PurchasesStatus', 1);
$newDB->where('UsersId', $_SESSION[_site_]['userid']);
$newDB->orderBy('p.PurchasesDate', 'DESC');
$table_data = $newDB->get ("Purchases p", null, "p.PurchasesId,p.PurchasesNo,p.PurchasesDate,s.SectionName as RequestSection, CONCAT('Purchase','') as ReceiveSection, p.IsUrgent as Urgent,CONCAT('<a href=\"print-purchase.php&quest;id=',p.PurchasesId,'\" target=\"_blank\" >','<i class=\"fas fa-print\"></i>', '</a>') as Print,CONCAT('<a href=\"addpurchaseorder.php&quest;id=',p.PurchasesId,'\" target=\"_blank\" >','Make PO', '</a>') as PO");
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
                <a href="purchaserequest.php" class="text-primary">Add request</a>
                <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
                  <thead>
                      <tr>
                        <th>PurchaseNo</th>
                        <th>PurchaseDate</th>
                        <th>RequestSection</th>
                        <th>Urgent</th>
                        <th>Print</th>
                        <th>Quotation</th>
                        <th>PO</th>
                      </tr>
                  </thead>

                  <tbody>
                      <?php
                        foreach ($table_data as $key => $value) {
                          if(!is_dir('quotation/'.$value['PurchasesId'])){
                            $haveQuotation = false;
                          }else{
                            if(glob('quotation/'.$value['PurchasesId'].'/*')){
                              $numQuo = count(glob('quotation/'.$value['PurchasesId'].'/*'));
                              $haveQuotation = true;
                            }else{
                              $haveQuotation = false;
                            }
                          }
                          
                          echo '<tr><td><a href="attach-quotation-files.php?id='.$value['PurchasesId'].'">'.$value['PurchasesNo'].'</a></td>';
                          echo '<td>'.$value['PurchasesDate'].'</td>';
                          echo '<td>'.$value['RequestSection'].'</td>';
                          echo '<td>'.($value['Urgent'] == 0? 'No':'<span class="text-danger">urgent</span>').'</td>';
                          echo '<td><a href="print-purchase.php?id='.$value['PurchasesId'].'" target="_blank"><i class="fas fa-pr
                          int"></i></a></td>';
                          if(!$haveQuotation){
                            echo '<td class="text-danger">no quotations</td>';
                            echo '<td class="text-danger">no quotations</td></tr>';
                          }else{
                            $po = $newDB->where('PurchasesId', $value['PurchasesId'])->getOne('purchaseorders');

                            echo '<td><a target="_blank" href="quotation/'.$value['PurchasesId'].'">'.$numQuo.' files</a></td>';
                            if(!$po){
                              echo '<td><a href="addpurchaseorder.php?id='.$value['PurchasesId'].'">Make PO</a></td></tr>';
                            }else if($po['PurchaseOrdersStatus'] == 0){
                              echo '<td><a href="addpurchaseorder.php?id='.$value['PurchasesId'].'">Update PO</a></td></tr>';
                            }else{
                              echo '<td><a href="addpurchaseorder.php?id='.$value['PurchasesId'].'">View submited PO</a></td></tr>';
                            }
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
