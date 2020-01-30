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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	//CHECK ID IS VALID
	if (!isset($_GET['id'])) {

		return 'Empty product id';

	} else if (!ctype_digit($_GET['id'])) {

		$errors[] = 'Invalid product id';
		return;

	} else {
    $id = (int)$_GET['id'];
  }
}



$pattern = $oDB->sl_one('LabelPattern','LabelPatternId='.$id);
//var_dump($pattern);
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
          <form action="listen-edit-pattern.php" method="post">
          <div class="row">
                 <div class="col-md-6">
                  <p><?php echo $oDB->lang('Station') ?></p>
                  <select name="TraceStationId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                    <?php 
                    $model = $oDB->sl_all('TraceStation',1);
                    foreach ($model as $key => $value) {
                      $selected = ($pattern['TraceStationId']==$value['TraceStationId']) ? 'Selected' : '' ;
                      echo "<option value='".$value['TraceStationId']."' ".$selected.">".$value['TraceStationName']."</option>";
                    }
                    ?>
                  </select>
                </Div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Products') ?></p>
                  <select name="ProductsId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                    <?php 
                    $model = $oDB->sl_all('Products','ProductsOption = 1 OR ProductsOption = 2');
                    foreach ($model as $key => $value) {
                      $selected = ($pattern['ProductsId']==$value['ProductsId']) ? 'Selected' : '' ;
                      echo "<option value='".$value['ProductsId']."' ".$selected.">".$value['ProductsNumber']."/".$value['ProductsName']."</option>";
                    }
                    ?>
                    
                  </select>
                </Div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('LabelPattern') ?></p>
                  <input type="text" name="LabelPatternValue" id="" class='form-control' value="<?php echo $pattern['LabelPatternValue'] ?>" required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('PackingStandard') ?></p>
                  <input type="text" name="LabelPatternPackingStandard" id="" class='form-control' value="<?php echo $pattern['LabelPatternPackingStandard'] ?>" required>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('PackingUnique') ?></p>
                  <select name="LabelPatternUnique" id="">
                    <option value="" <?php echo $retVal = ($pattern['LabelPatternUnique']=='') ? 'selected' : '' ; ?>><?php echo $oDB->lang('NotSet') ?></option>
                    <option value="1" <?php echo $retVal = ($pattern['LabelPatternUnique']==1) ? 'selected' : '' ; ?>><?php echo $oDB->lang('Yes') ?></option>
                    <option value="2" <?php echo $retVal = ($pattern['LabelPatternUnique']==2) ? 'selected' : '' ; ?>><?php echo $oDB->lang('No') ?></option>
                    <option value="3" <?php echo $retVal = ($pattern['LabelPatternUnique']==3) ? 'selected' : '' ; ?>><?php echo $oDB->lang('Yes - Not Trace') ?></option>
                  </select>
                </div>


                <input type="hidden" name="LabelPatternId" value='<?php echo $pattern['LabelPatternId'] ?>'>
                <div class="col-md-6">
                  <p>&nbsp;</p>
                  <button type="submit" class='btn-success form-control'><?php echo $oDB->lang('Submit')?></button>
                </div>

            </div>
          </form>

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
