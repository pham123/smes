<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/function.php');
require('../function/MysqliDb.php');
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$pagetitle = $user->module;
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();

$cashgroup = $oDB->sl_one('cashgroups', 'CashgroupsId = '.$_GET['id']);
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
          <form action="listen-update-cashgroup.php?id=<?php echo $_GET['id'] ?>" method="post">
              <div class="row">
                <div class="col-md-6">
                  <p><?php echo $oDB->lang('CashgroupName') ?></p>
                  <input type="text" name="CashgroupsName" id="" class='form-control' required value="<?php echo $cashgroup['CashgroupsName'] ?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('CashgroupCode') ?></p>
                  <input type="text" name="CashgroupsCode" id="" class='form-control' required value="<?php echo $cashgroup['CashgroupsCode'] ?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('CashgroupSecondName') ?></p>
                  <input type="text" name="CashgroupsSecondName" id="" class='form-control' value="<?php echo $cashgroup['CashgroupsSecondName'] ?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('CashgroupsUnit') ?>(Kg)</p>
                  <input type="text" name="CashgroupsUnit" id="" class='form-control' value="<?php echo $cashgroup['CashgroupsUnit'] ?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('CashgroupFrequency') ?>(Kg)</p>
                  <input type="number" name="CashgroupsFrequency" id="" class='form-control' value="<?php echo $cashgroup['CashgroupsFrequency']?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('CashgroupBudget') ?>(Kg)</p>
                  <input type="number" name="CashgroupsBudget" id="" class='form-control' value="<?php echo $cashgroup['CashgroupsBudget']?>">
                </div>


                <div class="col-md-6">
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
  <?php $oDB = null; $user = null;?>

  <script>
    $(function () {
      $('selectpicker').selectpicker();
    });



  </script>

</body>

</html>
