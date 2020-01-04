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


$issue = $oDB->sl_One('Memos','MemosId='.$id);

var_dump($issue);

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
                  <td colspan='3'><?php echo $issue['MemosName'] ?></td>
                  <td rowspan="16"><img src="./image/img_<?php echo $issue['MemosId']?>.jpg" style='width:400px'></td>
                </tr>

                <tr><td><?php echo $oDB->lang('IssueDate') ?></td><td colspan='3'><?php echo $issue['MemosCreateDate'] ?></td></tr>

                <tr><td><?php echo $oDB->lang('Parts') ?></td><td colspan=''><?php echo $issue['PartsId'] ?></td>
                <td><?php echo $oDB->lang('Areas') ?></td><td colspan=''><?php echo $issue['AreasId'] ?></td>
                </tr>

                <tr><td><?php echo $oDB->lang('Location') ?></td><td colspan='3'><?php echo $issue['MemosLocation'] ?></td></tr>
                <tr><td><?php echo $oDB->lang('MemosIssue') ?></td><td colspan='3'><?php echo $issue['MemosIssue'] ?></td></tr>

                <tr><td><?php echo $oDB->lang('MemosContent') ?></td><td colspan='3'><?php echo $issue['MemosContent'] ?></td></tr>
                <tr><td><?php echo $oDB->lang('MemosEfficiency') ?></td><td colspan='3'><?php echo $issue['MemosEfficiency'] ?></td></tr>
                <tr><td><?php echo $oDB->lang('MemosApplyDate') ?></td><td colspan='3'><?php echo $issue['MemosApplyDate'] ?></td></tr>
                
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
