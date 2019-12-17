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
$page_css = 'p{margin-bottom: 0px;}.col-md-6{padding-bottom: 10px;}';
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
          <h4>Add new spare part</h4>
          <form action="listen-create-sparepart.php" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <p><?php echo $oDB->lang('PartNo') ?> <sup class="text-danger">*</sup></p>
                  <input type="text" name="ProductsNumber" id="" class='form-control' required>
                </div>
                <div class="col-md-6">
                  <p><?php echo $oDB->lang('PartName') ?> <sup class="text-danger">*</sup></p>
                  <input type="text" name="ProductsName" id="" class='form-control' required>
                </div>
                <div class="col-md-6">
                  <p><?php echo $oDB->lang('EngName') ?></p>
                  <input type="text" name="ProductsEngName" id="" class='form-control'>
                </div>


                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Description') ?></p>
                  <input type="text" name="ProductsDescription" id="" class='form-control'>
                  <input type="hidden" name="ProductsOption" id="" value='4' class='form-control'>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Unit') ?></p>
                  <input type="text" name="ProductsUnit" id="" class='form-control'>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('NumberInStock') ?></p>
                  <input type="number" name="ProductsStock" id="" class='form-control' value="0">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('SafetyStk') ?></p>
                  <input type="text" name="ProductsSafetyStk" id="" class='form-control'>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('MinimumStk') ?></p>
                  <input type="text" name="ProductsMinimumStk" id="" class='form-control'>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Location') ?></p>
                  <input type="text" name="ProductsLocation" id="" class='form-control'>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Category') ?> <sup class="text-danger">*</sup></p>
                  <select name="ProductsCategory" id="" class="form-control" required>
                    <?php 
                    $ctes = $oDB->sl_all('categories',1);
                    echo "<option value=''>select category</option>";
                    foreach ($ctes as $key => $value) {
                      echo "<option value='".$value['CategoriesId']."'>".$value['CategoriesName']."</option>";
                    }
                    ?>
                    
                  </select>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('OrderStatus') ?></p>
                  <input type="text" name="ProductsOrderStatus" id="" class='form-control'>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Machine') ?></p>
                  <input type="text" name="ProductsMachine" id="" class='form-control'>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Note') ?></p>
                  <input type="text" name="ProductsNote" id="" class='form-control'>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('AddPicture') ?></p>
                  <input type="file" id='ingredient_file' name='fileToUpload' class="form-control" >  
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
