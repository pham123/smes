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
$datear = monthar($getdate,1);
// $_SESSION[_site_]['startdate']=$datear[0]['start'];
// $_SESSION[_site_]['enddate']=$datear[0]['end'];

$_SESSION[_site_]['startdate'] = (isset($_SESSION[_site_]['startdate'])) ? $_SESSION[_site_]['startdate'] : $datear[0]['start'] ;
$_SESSION[_site_]['enddate'] = (isset($_SESSION[_site_]['enddate'])) ? $_SESSION[_site_]['enddate'] : $datear[0]['end'] ;
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

if ($user->acess()==1) {
  $table_header  = 'No.,IssueDate,Dept,Location,title,issue,Picture,improve,PictureAfter,Efficiency,Maker,Pic,Status,ResultOfReview,Plan,Score,Edit,Delete';
}else{
  $table_header  = 'No.,IssueDate,Dept,Location,title,issue,Picture,improve,PictureAfter,Efficiency,Maker,Pic,Status,ResultOfReview,Plan,Score,Edit';
}

          
          $PartId = (isset($_GET['part'])) ? 'AND Memos.PartsId = '.safe($_GET['part']) : '' ;
          $MemosPic = (isset($_GET['pic'])) ? 'AND Memos.MemosPic = '.safe($_GET['pic']) : '' ;
          $creator = (isset($_GET['cr'])) ? 'AND Memos.MemosCreator = '.safe($_GET['cr']) : '' ;
          $status = (isset($_GET['st'])) ? 'AND Memos.MemosOption = '.safe($_GET['st']) : '' ;
          $scoretext='';
          // $score = (isset($_GET['score'])) ? safe($_GET['score']) : '' ;
          if (isset($_GET['score'])) {
            $score = safe($_GET['score']);
            switch ($score) {
              case '1':
                $scoretext = "AND Memos.MemosScore Between 80 AND 120";
                break;
              case '2':
                $scoretext = "AND Memos.MemosScore Between 50 AND 79";
                break;
              case '3':
                $scoretext = "AND (Memos.MemosScore < 50 OR Memos.MemosScore is Null)";
                break;                
              default:
                # code...
                break;
            }
          }

          $sql = "
          SELECT 
          Memos.*,
          Parts.PartsName,
          Areas.AreasName,
          MemoReduce.MemoReduceName,
          MemoApplicability.MemoApplicabilityName,
          Users.UsersFullName,
          Employees.EmployeesName,
          Employees.EmployeesCode
          FROM `Memos`
          INNER JOIN Parts ON Parts.PartsId = Memos.PartsId ".$PartId."
          INNER JOIN Areas ON Areas.AreasId = Memos.AreasId
          INNER JOIN MemoReduce ON MemoReduce.MemoReduceId = Memos.MemoReduceId
          INNER JOIN MemoApplicability ON MemoApplicability.MemoApplicabilityId = Memos.MemoApplicabilityId
          Inner JOIN Users ON Users.UsersId = Memos.MemosPic ".$MemosPic."
          INNER JOIN Employees ON Employees.EmployeesId = Memos.MemosCreator ".$creator."
          WHERE MemosCreateDate Between '".$_SESSION[_site_]['startdate']." 00:00:01' AND '".$_SESSION[_site_]['enddate']." 23:59:59'
          ".$status."
          ".$scoretext."
          Order by Memos.MemosId DESC
          ";

          $MemosList = $oDB->fetchAll($sql);
          
        ?>
        <div class="table-responsive" style="font-size: 80%;">
        <?php
        $tablearr = explode(',',$table_header);
        echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
        echo "<thead style='background-color:#CDCDCD;background-image: url('../img/bg/thead-bg.png');'>";
        echo "<tr style='background-color:#CDCDCD;background-image: url('../img/bg/thead-bg.png');'>";
        foreach ($tablearr as $key => $value) {
            echo "<th style='vertical-align: middle;'>".$oDB->lang($value)."</th>";
        }

        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($MemosList as $key => $value) {
          echo "<tr>";
          echo "<td>".($key+1)."</td>";
          echo "<td>".date("d M",strtotime($value['MemosCreateDate']))."</td>";
          echo "<td>".$value['PartsName']."</td>";
          // echo "<td>".$value['AreasName']."</td>";
          
          echo "<td>".$value['MemosLocation']."</td>";
          echo "<td style='min-width: 100px'><a href='ViewMemos.php?id=".$value['MemosId']."'>".$value['MemosName']."</a></td>";
          echo "<td style='min-width:180px;'>".$value['MemosIssue']."</td>";
          if (file_exists("image/small/img_".$value['MemosId'].".jpg")) {
          echo "<td><a href='image/img_".$value['MemosId'].".jpg?".filemtime("image/small/img_".$value['MemosId'].".jpg")."' target='_blank'><img src='image/small/img_".$value['MemosId'].".jpg?".filemtime("image/small/img_".$value['MemosId'].".jpg")."' alt=''></a></td>";
          }else{
            echo "<td><a href='image/img_".$value['MemosId'].".jpg' target='_blank'><img src='image/small/img_".$value['MemosId'].".jpg' alt=''></a></td>";  
          }
          // MemosContent
          echo "<td style='min-width:180px;'>".$value['MemosContent']."</td>";
          if (file_exists("image/small/imgafter_".$value['MemosId'].".jpg")) {
            echo "<td><a href='image/imgafter_".$value['MemosId'].".jpg?".filemtime("image/small/imgafter_".$value['MemosId'].".jpg")."' target='_blank'><img src='image/small/imgafter_".$value['MemosId'].".jpg?".filemtime("image/small/imgafter_".$value['MemosId'].".jpg")."' alt=''></a></td>";
          }else{
            echo "<td><a href='image/imgafter_".$value['MemosId'].".jpg' target='_blank'><img src='image/small/imgafter_".$value['MemosId'].".jpg' alt=''></a></td>";
          }
          
          echo "<td style='min-width:150px;'>".$value['MemosEfficiency']."</td>";

          
          echo "<td style='min-width: 100px'>".$value['EmployeesName']." - ".$value['EmployeesCode']."</td>";
          if ($value['MemosPic']==$_SESSION[_site_]['userid']) {
            if ($value['MemosPicOption']==2) {
              echo "<td style='background-color:red;'><a href='EditMemosPic.php?id=".$value['MemosId']."'>".$value['UsersFullName']."</a></td>";
            }else{
              echo "<td style='background-color:green;'><a href='EditMemosPic.php?id=".$value['MemosId']."'>".$value['UsersFullName']."</a></td>";
            }
          }else{
            if ($value['MemosPicOption']==2) {
              echo "<td style='background-color:red;'>".$value['UsersFullName']."</td>";
            }else{
              echo "<td style='background-color:green;'>".$value['UsersFullName']."</td>";
            }

          }

          // MemosPicExplain
          
          // echo "<td style=''>".$value['MemosPicExplain']."</td>";
          // echo "<td>".$value['MemosOption']."</td>";
          switch ($value['MemosOption']) {
            case '':
              echo "<td class=''>NA</td>";
              break;
            case '1':
              echo "<td class='bg-warning'>Doing</td>";
              break;
            case '2':
              echo "<td class='bg-success'>Done</td>";
              break;           
            case '3':
              echo "<td class='bg-danger'>Delay</td>";
              break;          
            default:
              echo "<td class='warning'>NA</td>";
              break;
          }
          switch ($value['MemosStatus']) {
            case '':
              echo "<td class=''>NA</td>";
              break;
            case '1':
              echo "<td class='bg-warning'>Xem xet</td>";
              break;
            case '2':
              echo "<td class='bg-success'>Duyệt</td>";
              break;           
            case '3':
              echo "<td class='bg-danger'>Hủy</td>";
              break;          
            default:
              echo "<td class='warning'>NA</td>";
              break;
          }
          echo "<td>".$value['MemosApplyDate']."</td>";
          echo "<td>".$value['MemosScore']."</td>";
          // $status = color($value['QualityIssuelistStatus']);
          // echo "<td style='background-color:".$status[0]."'>".$oDB->lang($status[1])."</td>";
          if ($user->acess()==1||$_SESSION[_site_]['userid']==$value['UsersId']) {
            echo "<td><a href='EditMemos.php?id=".$value['MemosId']."'><i class='fas fa-edit'></i></a></td>";
          }else{
            echo "<td></td>";
          }

          if ($user->acess()==1) {
            echo "<td><a href='DeleteMemos.php?id=".$value['MemosId']."'><i class='fas fa-trash-alt'></i></a></td>";
          }
          
          echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        ?>
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
