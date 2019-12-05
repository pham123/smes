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
$pagetitle = $user->module;
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
// echo $oDB->getcol('qualityissuelist');

?>
<!-- <meta http-equiv="refresh" content="30"> -->
<style>
p{margin:5px;}
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
        <div class="">
          <form action="listen-create-issue.php" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-12">
                  <p><?php echo $oDB->lang('Title') ?></p>
                  <input type="text" name="QualityIssuelistTitle" id="" class='form-control' required>
                </div>

                <div class="col-md-12">
                  <p><?php echo $oDB->lang('DefectContent') ?></p>
                  <textarea name="QualityIssuelistDefectiveContent" id="" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('IssueDate') ?></p>
                  <input type="date" name="QualityIssuelistDate" id="" class='form-control' value="<?php echo date('Y-m-d') ?>">
                </div>

                <div class="col-md-2">
                <p><?php echo $oDB->lang('ScmObject') ?></p>
                <select name="SupplyChainObjectId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('SupplyChainObject',1);
                  foreach ($model as $key => $value) {
                    echo "<option value='".$value['SupplyChainObjectId']."'>".$value['SupplyChainObjectName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <div class="col-md-3">
                <p><?php echo $oDB->lang('Products') ?></p>
                <select name="ProductsId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('Products','ProductsOption=1');
                  foreach ($model as $key => $value) {
                    echo "<option value='".$value['ProductsId']."'>".$value['ProductsName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <input type="hidden" name="QualityIssuelistCreator" value="<?php echo $_SESSION[_site_]['userid'] ?>">

                <div class="col-md-4">
                  <p><?php echo $oDB->lang('LotNo') ?></p>
                  <input type="text" name="QualityIssuelistLotNo" id="" class='form-control'>
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('ProductionDate') ?></p>
                  <input type="date" name="QualityIssuelistProductionDate" id="" class='form-control' value="<?php echo date('Y-m-d') ?>">
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('LotQuantity') ?></p>
                  <input type="number" name="QualityIssuelistLotQuantity" id="" class='form-control'>
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('NgQuantity') ?></p>
                  <input type="number" name="QualityIssuelistNgQuantity" id="" class='form-control'>
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('TimeOccurs') ?></p>
                  <input type="number" name="QualityIssuelistTimesOccurs" id="" class='form-control'>
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('DocNo') ?></p>
                  <input type="text" name="QualityIssuelistDocNo" id="" class='form-control'>
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('DueDate') ?></p>
                  <input type="date" name="QualityIssuelistDueDate" id="" class='form-control' value="<?php echo date('Y-m-d') ?>">
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('FinishDate') ?></p>
                  <input type="date" name="QualityIssuelistFinishDate" id="" class='form-control' value="<?php echo date('Y-m-d') ?>">
                </div>

                <div class="col-md-3">
                <p><?php echo $oDB->lang('Pic') ?></p>
                <select name="UsersId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('Users',1);
                  foreach ($model as $key => $value) {
                    echo "<option value='".$value['UsersId']."'>".$value['UsersName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('RootCause') ?></p>
                  <textarea name="QualityIssuelistRootCause" id="" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Action') ?></p>
                  <textarea name="QualityIssuelistAction" id="" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('IssuePicture') ?></p>
                  <input type="file" id='ingredient_file' name='issuepicture' class="form-control" >  
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('IssueReport') ?></p>
                  <input type="file" id='ingredient_file' name='issuereport' class="form-control" >  
                </div>

                <div class="col-md-3">

                <p><?php echo $oDB->lang('Status') ?></p>
                <select name="QualityIssuelistStatus" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                    <option value="1">Doing</option>
                    <option value="2">Done</option>
                    <option value="3">Delay</option>
                    <option value="4">Cancel</option>
                </select>
                </div>

                <div class="col-md-3">
                  <p>&nbsp;</p>
                  <button type="submit" class='btn btn-primary btn-block'><?php echo $oDB->lang('Submit') ?></button>
                </div>

              </div>
          </form>
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
