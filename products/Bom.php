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

?>

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
          <form action="listen-create-bom.php" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsDirectParent') ?></p>
                  <select name="BomsParentId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                    <?php 
                    $model = $oDB->sl_all('boms',1);
                    echo "<option value='0'>select parent BOM</option>";
                    foreach ($model as $key => $value) {
                      echo "<option value='".$value['BomsId']."'>".$value['BomsPartNo']."</option>";
                    }
                    ?>
                    
                  </select>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsPartNo') ?></p>
                  <input type="text" name="BomsPartNo" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsPartName') ?></p>
                  <input type="text" name="BomsPartName" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsSize') ?></p>
                  <input type="text" name="BomsSize" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsNet') ?>(Kg)</p>
                  <input type="number" step=".001" name="BomsNet" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsGloss') ?>(Kg)</p>
                  <input type="number" step=".001" name="BomsGloss" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsMaterial') ?></p>
                  <input type="text" name="BomsMaterial" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsUnit') ?></p>
                  <input type="text" name="BomsUnit" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsQty') ?></p>
                  <input type="number" name="BomsQty" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsProcess') ?></p>
                  <input type="text" name="BomsProcess" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsMaker') ?></p>
                  <input type="text" name="BomsMaker" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsClassifiedMaterial') ?></p>
                  <input type="text" name="BomsClassifiedMaterial" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('BomsMachine') ?></p>
                  <input type="text" name="BomsMachine" id="" class='form-control' required>
                </div>

                <div class="col-md-6">
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
