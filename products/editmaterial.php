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

$product = $oDB->sl_one('Products', 'ProductsId = '.$_GET['id']);
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
          <form action="listen-update-material.php?id=<?php echo $_GET['id'] ?>" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <p><?php echo $oDB->lang('ProductName') ?></p>
                  <input type="text" name="ProductsName" id="" class='form-control' required value="<?php echo $product['ProductsName'] ?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('ProductNumber') ?></p>
                  <input type="text" name="ProductsNumber" id="" class='form-control' required value="<?php echo $product['ProductsNumber'] ?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('ProductSize') ?></p>
                  <input type="text" name="ProductsSize" id="" class='form-control' value="<?php echo $product['ProductsSize'] ?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('ProductNet') ?>(Kg)</p>
                  <input type="number" value="0" min='0' step="0.001" name="ProductsNet" id="" class='form-control' value="<?php echo $product['ProductsNet']?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('ProductGloss') ?>(Kg)</p>
                  <input type="number" value="0" min='0' step="0.001" name="ProductsGloss" id="" class='form-control' value="<?php echo $product['ProductsGloss']?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('ProductMaterial') ?></p>
                  <input type="text" name="ProductsMaterial" id="" class='form-control' value="<?php echo $product['ProductsMaterial']?>">
                </div>
                <div class="col-md-6">
                  <p><?php echo $oDB->lang('ProductUnit') ?></p>
                  <input type="text" name="ProductsUnit" id="" class='form-control' value="<?php echo $product['ProductsUnit']?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('ProductDescription ') ?></p>
                  <input type="text" name="ProductsDescription" id="" class='form-control' required value="<?php echo $product['ProductsDescription'] ?>">
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Model') ?></p>
                  <select name="ModelsId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                    <?php 
                    $model = $oDB->sl_all('models',1);
                    foreach ($model as $key => $value) {
                      if($value['ModelsId'] == $product['ModelsId']){
                        echo "<option selected value='".$value['ModelsId']."'>".$value['ModelsName']."</option>";
                      } else {
                        echo "<option value='".$value['ModelsId']."'>".$value['ModelsName']."</option>";
                      }
                    }
                    ?>
                    
                  </select>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('EditPicture', 'Edit Picture') ?></p>
                  <input type="file" id='ingredient_file' name='fileToUpload' class="form-control" >
                  <br>  
                  <img style="max-height: 400px;" src="./image/img_<?php echo $product['ProductsId'] ?>.jpg" alt="">
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

  <script>
    $(function () {
      $('selectpicker').selectpicker();
    });



  </script>

</body>

</html>
