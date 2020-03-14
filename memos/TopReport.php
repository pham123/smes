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

$_SESSION[_site_]['startdate']=$startdate;
$_SESSION[_site_]['enddate']=$enddate;

// $DatePoint = date("Y-m");
// var_dump ($datear);

switch ($gettop) {
  case 'creator':
    $sql = "select 
            Memos.MemosCreator,
            Employees.EmployeesId As Id,
            Employees.EmployeesName As Name,
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
    $get = 'cr';
    break;

    case 'pic':
    $sql = "select 
              Memos.MemosPic,
              Users.UsersId as Id,
              Users.UsersFullName As Name,
              Count(*) as MemosTotal,
              SUM(case when MemosOption = 1 then 1 else 0 end) as MemosDoing,
              SUM(case when MemosOption = 2 then 1 else 0 end) as MemosDone,
              SUM(case when MemosOption = 3 then 1 else 0 end) as MemosDelay,
              SUM(case when MemosOption = 4 then 1 else 0 end) as MemosCancel
              from Memos
              INNER JOIN Users ON Users.UsersId = Memos.MemosPic
              WHERE date(Memos.MemosCreateDate) BETWEEN '".$startdate."' AND '".$enddate."'
              Group by Memos.MemosPic,Users.UsersFullName
              Order by MemosTotal DESC
              ";
      $get = 'pic';
      break;
      case 'score':
        $sql = "select 			
        (case when MemosScore between 80 and 120 then '1'
              when MemosScore between 50 and 79 then '2'
              when MemosScore < 50 OR MemosScore Is Null then '3'
            end) as Name,
              Memos.MemosPic,
              Users.UsersId as Id,
              Count(*) as MemosTotal,
              SUM(case when MemosOption = 1 then 1 else 0 end) as MemosDoing,
              SUM(case when MemosOption = 2 then 1 else 0 end) as MemosDone,
              SUM(case when MemosOption = 3 then 1 else 0 end) as MemosDelay,
              SUM(case when MemosOption = 4 then 1 else 0 end) as MemosCancel
              from Memos
              INNER JOIN Users ON Users.UsersId = Memos.MemosPic
              WHERE date(Memos.MemosCreateDate) BETWEEN '".$startdate."' AND '".$enddate."'
              Group by Name";
              $get = 'score';
        break;
  default:
    # code...
    break;
}



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
                <input type="date" name="startdate" class='form-control' id="" value='<?php echo $startdate ?>'>
              </div>
              <div class="col-md-2">
                <input type="date" name="enddate" class='form-control' id="" value='<?php echo $enddate ?>'>
              </div>
              <div class="col-md-2">
              <select name="top" id="" class='selectpicker show-tick form-control' data-live-search="true" data-style="btn-info" data-width="100%">
                  <option value="creator" <?php echo $retVal = ($gettop=='creator') ? 'selected' : '' ;?>>Creator</option>
                  <option value="pic" <?php echo $retVal = ($gettop=='pic') ? 'selected' : '' ;?>>Pic</option>
                  <option value="score" <?php echo $retVal = ($gettop=='score') ? 'selected' : '' ;?>>score</option>
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
        <?php
switch ($gettop) {
  case 'score':
    include('temp-top-score.php');
    break;
  
  default:
    include('temp-top-maker.php');
    break;
}
        ?>
        
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
