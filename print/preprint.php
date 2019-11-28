<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
$currentlocation = basename(dirname(__FILE__));
require('../function/function_start.php');
require('../config.php');
require('../function/db_lib.php');
require('../lang/en.php');
$page = 'system';
$pagetitle = _System_;
// $refresh = 2;
require('../views/template-header.php');
require('../function/template.php');
$id = safe($_GET['id']);
$oDB = new db();
$products = new products();
$products->get($id);
$products->lang="Vi";

// $actionar = (array_keys($_GET));
// $actionkey = (isset($actionar[0])) ? $actionar[0] : 'content' ;

// $action =  (explode("_",$actionkey));

// $option = $action[0];
// $target = (isset($action[1])) ? ucfirst($action[1]) : 'Company' ;
// $id = (isset($action[2])) ? $action[2] : 1 ;

// if (file_exists('../querry/'.$option.'_'.$target.'.php')) {
//   require('../querry/'.$option.'_'.$target.'.php');
// }else {
//   $sql = "Select * from ".$target;
// }

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

        <?php 
          
          //var_dump($oDB->getcol('products'));

        ?>

        <div class="table-responsive">
            <form action="print.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id ?>">
               
                <div class="row">
                    <div class="form-group col-md-6" >
                        <label for="exampleInputEmail1"><?php echo $products->lang("PartName") ?></label>
                        <input type="text" class="form-control" id="exampleInputEmail1" value='<?php echo $products->name ?>' readonly>
                    </div>

                    <div class="form-group col-md-6" >
                        <label for="exampleInputEmail1"><?php echo $products->lang("PartNumber") ?></label>
                        <input type="text" class="form-control" id="exampleInputEmail1" value='<?php echo $products->number ?>' readonly>
                    </div>
                    <div class="form-group col-md-2" >
                        <label for="exampleInputEmail1"><?php echo $products->lang("Cavity") ?></label>
                        <input type="number" name='cavity' class="form-control" required>
                    </div>
                    <div class="form-group col-md-4" >
                        <label for="exampleInputEmail1"><?php echo $products->lang("Date") ?></label>
                        <input type="date" name='selectdate' class="form-control" value='<?php echo date('Y-m-d') ?>'>
                    </div>
                    <div class="form-group col-md-2" >
                        <label for="exampleInputEmail1"><?php echo $products->lang("Lot") ?></label>
                        <input type="number" name='lot' class="form-control" required>
                    </div>
                    <div class="form-group col-md-2" >
                        <label for="exampleInputEmail1"><?php echo $products->lang("Shift") ?></label>
                        <select name="shift" id="" class="form-control" required>
                            <option value=""></option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2" >
                        <label for="exampleInputEmail1"><?php echo $products->lang("Quantity") ?></label>
                        <input type="number" name='quantity' class="form-control" required>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary"><?php echo $products->lang("Submit") ?></button>
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
