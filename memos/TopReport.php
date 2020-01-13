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


getfunc('monthar');
$getdate = (isset($_GET['date'])) ? safe($_GET['date']) : date('Y-m-d') ;
$gettop = (isset($_GET['top'])) ? safe($_GET['top']) : 'creator' ;
$datear = monthar($getdate,1);
$startdate = (isset($_GET['startdate'])) ? safe($_GET['startdate']) : date('Y-m-01') ;
$enddate = (isset($_GET['enddate'])) ? safe($_GET['enddate']) : date('Y-m-t') ;

// $DatePoint = date("Y-m");
// var_dump ($datear);


$sql = "select 
Memos.MemosCreator,
Employees.EmployeesName,
Count(*) as MemosTotal,
SUM(case when MemosOption = 1 then 1 else 0 end) as MemosDoing,
SUM(case when MemosOption = 2 then 1 else 0 end) as MemosDone,
SUM(case when MemosOption = 3 then 1 else 0 end) as MemosDelay,
SUM(case when MemosOption = 4 then 1 else 0 end) as MemosCancel
from Memos
INNER JOIN Employees ON Employees.EmployeesId = Memos.MemosCreator
WHERE date(Memos.MemosCreateDate) BETWEEN '".$startdate."' AND '".$enddate."'
Group by Memos.MemosCreator,Employees.EmployeesName
Order by MemosTotal DESC
";

$report = $oDB->fetchAll($sql);
// var_dump($report);
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


        <form action="" method="get">
            <div class="row">

              <div class="col-md-2">
                <input type="date" name="startdate" class='form-control' id="" value='<?php echo date("Y-m-01") ?>'>
              </div>
              <div class="col-md-2">
                <input type="date" name="enddate" class='form-control' id="" value='<?php echo date("Y-m-t") ?>'>
              </div>
              <div class="col-md-2">
              <select name="top" id="" class='selectpicker show-tick form-control' data-live-search="true" data-style="btn-info" data-width="100%">
                  <option value="creator">Creator</option>
              </select>
            </div>
            <div class="col-md-2">
            <button type="submit" class='form-control'>Submit</button>
            </div>
            </div>
        </form>
      

        <!-- <div class="table-responsive">
          <div id="chart_div" style="width: 100%; height: 500px;"></div>
        </div> -->

        </br>
        <div class="row">
        <div class="col-md-8">
        <table class='table table-bordered table-sm' id='' width='100%' cellspacing='0'>
                  <thead>
                    <tr>
                      <th rowspan='2' class='text-center align-middle'>Name</th>
                      <th rowspan='2' class='text-center align-middle'>Total</th>
                      <th colspan='4' class='text-center align-middle'>Status</th>
                    </tr>
                    <tr>
                      <th class='text-center align-middle'>Done</th>
                      <th class='text-center align-middle'>Doing</th>
                      <th class='text-center align-middle'>Delay</th>
                      <th class='text-center align-middle'>Cancel</th>
                    </tr>
                  </thead>

                  <tbody>
                  <?php
                  $Total = 0;
                  $Done = 0;
                  $Doing = 0;
                  $Delay = 0;
                  $Cancel = 0;
                  foreach ($report as $key => $value) {
                    $Total += $value['MemosTotal'];
                    $Done += $value['MemosDone'];
                    $Doing += $value['MemosDoing'];
                    $Delay += $value['MemosDelay'];
                    $Cancel += $value['MemosCancel'];
                   ?>
                    <tr class='text-center align-middle'>
                      <td ><?php echo $value['EmployeesName'] ?></td>
                      <td ><?php echo $value['MemosTotal'] ?></td>
                      <td class='bg-success'><?php echo $value['MemosDone'] ?></td>
                      <td class='bg-warning'><?php echo $value['MemosDoing'] ?></td>
                      <td class='bg-danger'><?php echo $value['MemosDelay'] ?></td>
                      <td><?php echo $value['MemosCancel'] ?></td>
                    </tr>
                   <?php
                  }
                  ?>
                    <tr class='text-center align-middle'>
                      <td style='font-weight:bold'><?php echo $oDB->lang('Total') ?></td>
                      <td style='font-weight:bold'><?php echo $Total ?></td>
                      <td style='font-weight:bold' class='bg-success'><?php echo $Done ?></td>
                      <td style='font-weight:bold' class='bg-warning'><?php echo $Doing ?></td>
                      <td style='font-weight:bold' class='bg-danger'><?php echo $Delay ?></td>
                      <td style='font-weight:bold'><?php echo $Cancel ?></td>
                    </tr>
                  

                  </tbody>
        </table>    
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

</body>

</html>
