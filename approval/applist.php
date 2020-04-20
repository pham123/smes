<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/MysqliDb.php');
require('../function/sdb.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$pagetitle = $user->module;
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 250px;overflow: hidden;font-size: 14px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$sDB = new sdb();
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);

?>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">

  <?php require('sidebar.php') ?>
  <?php
    switch ($_GET['type']) {
      case 1:
        $data = totalReceived();
        $title = 'Received apps';
        break;
      case 2:
        $title = 'Waiting your approval';
        $data = waitingYourApproval();
        break;
      case 3:
        $title = 'Waiting final approval';
        $data = waitingFinalApproval();
        break;
      case 4:
        $title = 'Your rejected list';
        $data = yourRejectedList();
        break;
      case 5:
        $title = 'Your created apps';
        $data = yourCreatedApp();
        break;
      default:
        header("Location:index.php");
    }

    function generateAppStatus($id){
      global $newDB;
      $status = '';
      $step=0;

      $newDB->where('PurchasePaymentsId', $id);
      $pplas = $newDB->get('purchasepaymentlineapproval');
      $numOfLines = count($pplas);

      foreach($pplas as $ppla){
        switch ($ppla['LineStatus']) {
          case 1:
            $step++;
            $status='warning';
            break;
          case 2:
            $step++;
            $status='success';
            break;
          case 3:
            $step++;
            $status='danger';
            break;
          default:
            break;
        }
        if($status=='danger') break;
      }
      if($status == 'danger' || $status== 'success'){
        $className = '';
      }else{
        $className = 'text-secondary';
      }

      echo "<div class='progress'>
      <div class='progress-bar progress-bar-striped progress-bar-animated bg-".$status."' role='progressbar' style='width: ".($step*100/$numOfLines)."%'><span class='".$className."'>".$step."/".$numOfLines."</span></div>
    </div>";
    }
  ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
        
        <!-- Topbar -->
        <?php require('navbar.php') ?>

        <div class="row">
          <h5 class="px-3"><?php echo $title ?></h5>
          <div class="col-12 px-3">
            <table class='table table-bordered table-sm' id='dataTable' width='100%' cellspacing='0'>
            <thead>
                <tr>
                  <th>Title</th>
                  <th>PO number</th>
                  <th>Cashgroup</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Receive date</th>
                  <th>Urgent</th>
                  <th>Status</th>
                </tr>
            </thead>

            <tbody>
              <?php
                foreach($data as $d){
              ?>
                <tr>
                  <td>
                    <?php
                      if($_GET['type'] ==2){
                    ?>
                      <a href="approveorrejectapp.php?id=<?php echo $d['PurchasePaymentsId']?>"><?php echo $d['PurchasePaymentsTitle']?></a>
                    <?php
                      } else {
                    ?>
                    <a href="viewapp.php?id=<?php echo $d['PurchasePaymentsId']?>"><?php echo $d['PurchasePaymentsTitle']?></a>
                    <?php
                      }
                    ?>
                    
                  </td>
                  <td><?php echo $d['PurchaseOrdersNo']?></td>
                  <td><?php echo $d['CashgroupsName']?></td>
                  <td><?php echo number_format($d['PurchasePaymentsAmount'],0,',','.')?></td>
                  <td><?php echo $d['PurchasePaymentsDate']?></td>
                  <td><?php echo $d['PurchasePaymentsReceiveDate']?></td>
                  <td><?php if($d['IsUrgent'] == 0) echo 'no'; else echo 'yes'; ?></td>
                  <td><?php generateAppStatus($d['PurchasePaymentsId'])?></td>
                </tr>
              <?php
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
