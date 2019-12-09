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
$access = $user->acess();
$pagetitle = $user->module;
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
if (is_numeric($_GET['id'])) {
  $id = safe($_GET['id']) ;
  $_SESSION[_site_]['editissueid']=$id;
}else{
  header ('Location: index.php');
  exit();
}
$issue = $oDB->sl_One('QualityIssuelist','QualityIssuelistId='.$id);


// echo $oDB->getcol('qualityissuelist');

?>
<!-- <meta http-equiv="refresh" content="30"> -->
<style>
p{margin:5px;}

<style>
  .ck-editor__editable {
      min-height: 400px;
  }

  
</style>
</style>
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
            <!-- . QualityIssuelistId. QualityIssuelistTitle. QualityIssuelistDate. SupplyChainObjectId. ProductsId. QualityIssuelistCreator. QualityIssuelistLotNo. QualityIssuelistProductionDate. QualityIssuelistDefectiveContent. QualityIssuelistLotQuantity. QualityIssuelistNgQuantity. QualityIssuelistTimesOccurs. QualityIssuelistDocNo. QualityIssuelistDueDate. QualityIssuelistFinishDate. QualityIssuelistRootCause. QualityIssuelistAction. UsersId. QualityIssuelistStatus. QualityIssuelistOption. QualityIssuelistCreateDate. QualityIssuelistUpdateDate -->
              <div class="col-md-12">
              <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
                <tr>
                  <td><?php echo $oDB->lang('Title') ?></td>
                  <td colspan='3'><?php echo $issue['QualityIssuelistTitle'] ?></td>
                  <td rowspan="16"><img src="./image/img_<?php echo $issue['QualityIssuelistId']?>.jpg" style='width:400px'></td>
                </tr>

                <tr><td><?php echo $oDB->lang('IssueDate') ?></td><td colspan='3'><?php echo $issue['QualityIssuelistDate'] ?></td></tr>
                <tr><td><?php echo $oDB->lang('Object') ?></td><td colspan='3'><?php echo $oDB->get_one('SupplyChainObjectName','SupplyChainObject',$issue['SupplyChainObjectId']) ?></td></tr>
                <tr><td><?php echo $oDB->lang('Products') ?></td><td colspan='3'><?php echo $oDB->get_one('ProductsNumber','products',$issue['ProductsId']) ?> | <?php echo $oDB->get_one('ProductsName','products',$issue['ProductsId']) ?></td></tr>
                <tr><td><?php echo $oDB->lang('Creator') ?></td><td><?php echo $oDB->get_one('UsersFullName','Users',$issue['QualityIssuelistCreator']) ?></td><td><?php echo $oDB->lang('Pic') ?></td><td><?php echo $oDB->get_one('UsersFullName','Users',$issue['UsersId']) ?></td></tr>
                <tr><td><?php echo $oDB->lang('LotNo') ?></td><td><?php echo $issue['QualityIssuelistLotNo'] ?></td><td><?php echo $oDB->lang('ProductionDate') ?></td><td><?php echo $issue['QualityIssuelistProductionDate'] ?></td></tr>
                <tr><td><?php echo $oDB->lang('DefectiveContent ') ?></td><td colspan="3"><?php echo $issue['QualityIssuelistDefectiveContent'] ?></td></tr>
                <tr><td><?php echo $oDB->lang('DefectRate') ?></td><td><?php echo $issue['QualityIssuelistNgQuantity'] ?>/<?php echo $issue['QualityIssuelistLotQuantity'] ?> (<?php echo 100*round($issue['QualityIssuelistNgQuantity']/$issue['QualityIssuelistLotQuantity'],2) ?>%)</td><td><?php echo $oDB->lang('TimeOccurs') ?></td><td><?php echo $issue['QualityIssuelistTimesOccurs'] ?></td></tr>
                <tr><td><?php echo $oDB->lang('RootCause') ?></td><td colspan="3"><?php echo $issue['QualityIssuelistRootCause'] ?></td></tr>
                <tr><td><?php echo $oDB->lang('Action') ?></td><td colspan="3"><?php echo $issue['QualityIssuelistAction'] ?></td></tr>
                <tr><td><?php echo $oDB->lang('ScheduleDate') ?></td><td><?php echo $issue['QualityIssuelistDueDate'] ?></td><td><?php echo $oDB->lang('FinishDate') ?></td><td><?php echo $issue['QualityIssuelistFinishDate'] ?></td></tr>
                <?php $status=color($issue['QualityIssuelistStatus']) ?>
                <tr><td><?php echo $oDB->lang('ReportFile') ?></td><td><a href="./files/file_<?php echo $issue['QualityIssuelistId'] ?>.pptx">Report</a></td><td><?php echo $oDB->lang('Status') ?></td><td style="background-color:<?php echo $status[0] ?>"><?php echo $oDB->lang($status[1]); ?></td></tr>


                </table>
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
