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
$page_heading = 'UpdateExport';
$page_css = 'p{margin-bottom: 0px;}.col-md-6{padding-bottom: 10px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();

$export = $oDB->sl_one('exports', 'ExportsId = '.$_GET['id']);
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
          <form action="listen-update-export.php?id=<?php echo $_GET['id'] ?>" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <p>Export PO</p>
                  <input type="text" name="ExportsPO" id="" class='form-control' required value="<?php echo $export['ExportsPO'] ?>">
                </div>
                <div class="col-md-6">
                  <p>Export Date</p>
                  <input type="date" name="ExportsDate" id="" class='form-control' required value="<?php echo $export['ExportsDate'] ?>">
                </div>
                <div class="col-md-6">
                  <p>Bộ phận</p>
                  <select name="SectionId" id="" class='form-control'>
                    <?php 
                    $model = $oDB->sl_all('section',1);
                    foreach ($model as $key => $value) {
                      if($value['SectionId'] == $export['SectionId']){
                        echo "<option selected value='".$value['SectionId']."'>".$value['SectionName']."</option>";
                      } else {
                        echo "<option value='".$value['SectionId']."'>".$value['SectionName']."</option>";
                      }
                    }
                    ?>
                    
                  </select>
                </div>
                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Quantity') ?></p>
                  <input type="number" name="ProductsQty" id="" class='form-control' value="<?php echo $export['ProductsQty'] ?>">
                </div>
                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Product') ?></p>
                  <select name="ProductsId" id="" class='form-control'>
                    <?php 
                    $model = $oDB->sl_all('products','ProductsOption = 4');
                    foreach ($model as $key => $value) {
                      if($value['ProductsId'] == $export['ProductsId']){
                        echo "<option selected value='".$value['ProductsId']."'>".$value['ProductsNumber']."</option>";
                      } else {
                        echo "<option value='".$value['ProductsId']."'>".$value['ProductsNumber']."</option>";
                      }
                    }
                    ?>
                    
                  </select>
                </div>
                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Note') ?></p>
                  <input type="text" name="ExportsNote" id="" class='form-control' value="<?php echo $export['ExportsNote'] ?>">
                </div>
                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Reason') ?></p>
                  <input type="text" name="ExportsReason" id="" class='form-control' value="<?php echo $export['ExportsReason'] ?>">
                </div>

                <div class="col-md-6">
                  <br>
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