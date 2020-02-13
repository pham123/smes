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
$stationid = 5;
$oDB = new db();
$nam = date("Y");
$thang = date("m");
$ngaycuoithang = date("t",strtotime($nam."-".$thang."-01"));
$ngayarr = array();

// $shiftarray[0]['start']= date("Y-m-d 08:00:00");
//             $shiftarray[0]['end']= date("Y-m-d 20:00:00");
//             $shiftarray[1]['start']= date ("Y-m-d 20:00:00",strtotime('-1 days'));
//             $shiftarray[1]['end']= date("Y-m-d 08:00:00");
for ($i=0; $i < $ngaycuoithang; $i++) { 
  $date = $i+1;
  $ngay = $nam."-".$thang."-".$date;
  $ngayarr[$i]['start'] = date("Y-m-d 20:00:00",strtotime($ngay.'-1 days'));
  $ngayarr[$i]['end'] = date("Y-m-d 20:00:00",strtotime($ngay));
}
// echo "<pre>";
// var_dump($ngayarr);
// echo "</pre>";

//exit();
?>
<style>
  td,th{
    color:black;
  }
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

        <?php 
          $table_data = $oDB->sl_all('LabelPattern','1');
          $sumtext = '';
          foreach ($ngayarr as $key => $value) {
            $date = $key+1;
            $sumtext = $sumtext."
            SUM(CASE WHEN lh.LabelHistoryCreateDate BETWEEN '".$value['start']."' AND '".$value['end']."' THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS TotalOk".$date.",";
          }

          $lastelement = $ngaycuoithang -1;
          $sql = "SELECT 
          prd.ProductsName,
          prd.ProductsNumber,
          ".$sumtext."
          ts.TraceStationName
          FROM LabelHistory lh
          INNER JOIN Products prd ON prd.ProductsId = lh.ProductsId
          INNER JOIN TraceStation ts ON ts.TraceStationId = lh.TraceStationId AND ts.TraceStationId = ".$stationid."
          WHERE lh.LabelHistoryCreateDate BETWEEN '".$ngayarr[0]['start']."' AND '".$ngayarr[$lastelement]['end']."'
          GROUP BY prd.ProductsName, prd.ProductsNumber, ts.TraceStationName";

         // exit();

          $result = $oDB->fetchAll($sql);
        ?>

        <div class="table-responsive">
        <?php
        echo "<table class='table table-bordered' id='datatablenotpage' width='100%' cellspacing='0'>";
        echo "<thead>";
        echo "<tr>";
            // echo "<th>".$oDB->lang('Index')."</th>";
            // echo "<th>".$oDB->lang('Station')."</th>";
            echo "<th rowspan='2'>".$oDB->lang('ProductName')."</th>";
            echo "<th rowspan='2'>".$oDB->lang('ProductNumber')."</th>";
            echo "<th colspan='".$ngaycuoithang."'>".$oDB->lang('QuantityReport').":".date("Y-M",strtotime($ngayarr[0]['end']))."</th>";
        echo "</tr>";
        echo "<tr>";
            foreach ($ngayarr as $key => $value) {
              echo "<th>".($key+1)."</th>";
            }
        echo "</tr>";
        echo "</thead>";


        echo "<tbody>";

        foreach ($result as $key => $value) {
            echo "<tr>";
            // echo "<td>".($key+1)."</td>";
            // echo "<td>".$value['TraceStationName']."</td>";
            echo "<td>".trim(preg_replace('/\s+/', '_', $value['ProductsName']))."</td>";
            echo "<td>".$value['ProductsNumber']."</td>";
            for ($i=1; $i < $ngaycuoithang+1; $i++) { 
              $retVal = ($value['TotalOk'.$i]==0) ? '' : $value['TotalOk'.$i] ;
              echo "<td>".$retVal."</td>";
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
