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
$sql = "
          SELECT 
          Memos.*,
          Parts.PartsName,
          Areas.AreasName,
          MemoReduce.MemoReduceName,
          MemoApplicability.MemoApplicabilityName,
          Users.UsersFullName,
          Employees.EmployeesName
          FROM `memos`
          INNER JOIN Parts ON Parts.PartsId = Memos.PartsId
          INNER JOIN Areas ON Areas.AreasId = Memos.AreasId
          INNER JOIN MemoReduce ON MemoReduce.MemoReduceId = Memos.MemoReduceId
          INNER JOIN MemoApplicability ON MemoApplicability.MemoApplicabilityId = Memos.MemoApplicabilityId
          Inner JOIN Users ON Users.UsersId = Memos.MemosPic
          INNER JOIN Employees ON Employees.EmployeesId = Memos.MemosCreator
          WHERE MemosId =".$id."
          ";

  // $rs = $oDB->mysqli_fetch_assoc($sql);
  $issue = $oDB->fetchOne($sql);

// var_dump($issue);

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
              <div class="col-md-12">
              <form action="listen-edit-memos-pic.php?id=<?php echo $issue['MemosId'] ?>" method="post" enctype="multipart/form-data">
              <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
              
                <tr>
                  <td><?php echo $oDB->lang('Title') ?></td>
                  <td colspan='3'><?php echo $issue['MemosName'] ?></td>
                  <td rowspan="16"><img src="./image/img_<?php echo $issue['MemosId']?>.jpg" style='width:400px'></td>
                </tr>

                <tr><td><?php echo $oDB->lang('IssueDate') ?></td><td colspan='3'><?php echo $issue['MemosCreateDate'] ?></td></tr>

                <tr><td><?php echo $oDB->lang('Parts') ?></td><td colspan=''><?php echo $issue['PartsName'] ?></td>
                <td><?php echo $oDB->lang('Areas') ?></td><td colspan=''><?php echo $issue['AreasName'] ?></td>
                </tr>

                <tr><td><?php echo $oDB->lang('Location') ?></td><td colspan='3'><?php echo $issue['MemosLocation'] ?>
                </td></tr>

                <tr><td><?php echo $oDB->lang('MemoReduce') ?></td><td colspan=''><?php echo $issue['MemoReduceName'] ?></td>
                <td><?php echo $oDB->lang('MemoApplicability') ?></td><td colspan=''><?php echo $issue['MemoApplicabilityName'] ?></td>
                </tr>
                
                
                <tr><td><?php echo $oDB->lang('MemosIssue') ?></td><td colspan='3'>
                <?php echo $issue['MemosIssue'] ?>
                </td></tr>

                <tr><td><?php echo $oDB->lang('MemosContent') ?></td><td colspan='3'>
                <?php echo $issue['MemosContent'] ?>
                </td></tr>
                <tr><td><?php echo $oDB->lang('MemosEfficiency') ?></td><td colspan='3'>               
                <?php echo $issue['MemosEfficiency'] ?>
                </td></tr>

                <tr><td><?php echo $oDB->lang('MemosCreator') ?></td><td colspan='3'><?php echo $issue['EmployeesName'] ?></td></tr>
                <tr><td><?php echo $oDB->lang('MemosPic') ?></td><td colspan=''>
                <select name="MemosPic" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%" disabled>
                  <?php 
                  $Users = $oDB->sl_all('Users',1);
                  foreach ($Users as $key => $value) {
                    $select = ($issue['MemosPic']==$value['UsersId']) ? 'selected' : '' ;
                    echo "<option value='".$value['UsersId']."' ".$select.">".$value['UsersFullName']."</option>";
                  }
                  ?>
                </select>
                
                </td>
                <td>Pic's Accept</td>
                <td>
                  <select name="MemosPicOption" id="">
                    <option value="1" <?php echo $retVal = ($issue['MemosPicOption']==1) ? 'Selected' : '' ; ?>>Đồng ý</option>
                    <option value="2" <?php echo $retVal = ($issue['MemosPicOption']==2) ? 'Selected' : '' ; ?>>Không đồng ý</option>
                  </select>
                </td>
                </tr>

                <tr>
                <td>Explain</td>
                <td colspan='3'>
                <textarea name="MemosPicExplain" id="" class='form-control' ><?php echo $issue['MemosPicExplain'] ?></textarea>
                </td>
                </tr>



                <tr><td><?php echo $oDB->lang('MemosApplyDate') ?></td><td colspan=''><?php echo $issue['MemosApplyDate'] ?></td>
                <td><?php echo $oDB->lang('ReportFile') ?></td><td><a href="./files/files_<?php echo $issue['MemosId'] ?>.pptx">Report</a></td>
                </tr>

                <tr>
                  <td><?php echo $oDB->lang('PictureAfter') ?></td><td><input type="file" id='ingredient_file' name='MemosPictureAfter' class="form-control" ></td>
                  <td><?php echo $oDB->lang('UpdateFile') ?></td><td><input type="file" id='ingredient_file' name='MemosReport' class="form-control" ></td>
                </tr>

                <tr>
                  <td colspan='4'><button type="submit" class='form-control btn btn-success'><?php echo $oDB->lang('Update') ?></button></td>
                </tr>


                </table>
                </form>
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
