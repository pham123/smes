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
//echo $user->id;

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
          <form action="listen-create-memos.php" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-3">
                <p><?php echo $oDB->lang('Parts') ?></p>
                <select name="PartsId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('Parts',1);
                  foreach ($model as $key => $value) {
                    echo "<option value='".$value['PartsId']."'>".$value['PartsName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <div class="col-md-3">
                <p><?php echo $oDB->lang('Areas') ?></p>
                <select name="AreasId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('Areas',1);
                  foreach ($model as $key => $value) {
                    echo "<option value='".$value['AreasId']."'>".$value['AreasName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <div class="col-md-3">
                <p><?php echo $oDB->lang('MemoReduce') ?></p>
                <select name="MemoReduceId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('MemoReduce',1);
                  foreach ($model as $key => $value) {
                    echo "<option value='".$value['MemoReduceId']."'>".$value['MemoReduceName']."</option>";
                  }
                  ?>
                </select>
                </div>
                <!-- MemoApplicabilityId -->

                <div class="col-md-3">
                <p><?php echo $oDB->lang('MemoApplicability') ?></p>
                <select name="MemoApplicabilityId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('MemoApplicability',1);
                  foreach ($model as $key => $value) {
                    echo "<option value='".$value['MemoApplicabilityId']."'>".$value['MemoApplicabilityName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <div class="col-md-12">
                  <p><?php echo $oDB->lang('Location') ?></p>
                  <input type="text" name="MemosLocation" id="" class='form-control'>
                </div>

                <div class="col-md-12">
                  <p><?php echo $oDB->lang('Title') ?></p>
                  <input type="text" name="MemosName" id="" class='form-control'>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('MemosIssue') ?></p>
                  <textarea name="MemosIssue" id="" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('MemosImprovementContent') ?></p>
                  <textarea name="MemosContent" id="" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-md-12">
                  <p><?php echo $oDB->lang('MemosEfficiency') ?></p>
                  <input type="text" name="MemosEfficiency" id="" class='form-control'>
                </div>



                <input type="hidden" name="UsersId" value="<?php echo $_SESSION[_site_]['userid'] ?>">

                <!-- employees -->
                <div class="col-md-4">
                <p><?php echo $oDB->lang('Creator') ?></p>
                <select name="MemosCreator" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('Employees',1);
                  foreach ($model as $key => $value) {
                    $select = ($user->id==$value['EmployeesId']) ? 'selected' : '' ;
                    echo "<option value='".$value['EmployeesId']."' ".$select.">".$value['EmployeesCode']."-".$value['EmployeesName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <div class="col-md-4">
                <p><?php echo $oDB->lang('Pic') ?></p>
                <select name="MemosPic" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('Users',1);
                  foreach ($model as $key => $value) {
                    $select = ($user->id==$value['UsersId']) ? 'selected' : '' ;
                    echo "<option value='".$value['UsersId']."' ".$select.">".$value['UsersFullName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <div class="col-md-4">
                  <p><?php echo $oDB->lang('ApplyDate') ?></p>
                  <input type="date" name="MemosApplyDate" id="" class='form-control' value="<?php echo date('Y-m-d') ?>">
                </div>



                <div class="col-md-4">
                  <p><?php echo $oDB->lang('MemosPicture') ?></p>
                  <input type="file" id='ingredient_file' name='MemosPicture' class="form-control" >  
                </div>

                <div class="col-md-4">
                  <p><?php echo $oDB->lang('MemosReport') ?></p>
                  <input type="file" id='ingredient_file' name='MemosReport' class="form-control" >  
                </div>

                <div class="col-md-4">

                <p><?php echo $oDB->lang('Status') ?></p>
                <select name="MemosOption" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                    <option value="1">Doing</option>
                    <option value="2">Done</option>
                    <option value="3">Delay</option>
                    <option value="4">Cancel</option>
                </select>
                </div>

                <div class="col-md-12">
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
