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
$getdate = (isset($_GET['year'])) ? safe($_GET['year']) : date('Y') ;
$starty = $getdate."-01-01";
$endy = $getdate."-12-31";
// $datear = monthar($getdate,1);
$_SESSION[_site_]['startdate']=$starty;
$_SESSION[_site_]['enddate']=$endy;

// $DatePoint = date("Y-m");
// var_dump ($datear);

$text = '';
// for ($i=1; $i < 13 ; $i++) { 
//   $datetext = $getdate."-".$i."-01";
//   $text .= "
//   SUM(case when MemosOption = 1 AND then 1 else 0 end) as MemosDoing,
//   SUM(case when MemosOption = 2 AND then 1 else 0 end) as MemosDone,
//   SUM(case when MemosOption = 3 AND then 1 else 0 end) as MemosDelay,
//   SUM(case when MemosOption = 4 AND then 1 else 0 end) as MemosCancel,
//   ";
// }

for ($i=1; $i < 13 ; $i++) { 
  $datetext = $getdate."-".$i."-01";
  $text .= "
  SUM(case when MemosCreateDate between '".$datetext."' AND '".date("Y-m-t",strtotime($datetext))."' then 1 else 0 end) as Memos".date("M",strtotime($datetext)).",";
}



$sql = "select 
  Memos.PartsId,
  ".$text."
  SUM(case when MemosOption = 1 then 1 else 0 end) as MemosDoing,
  SUM(case when MemosOption = 2 then 1 else 0 end) as MemosDone,
  SUM(case when MemosOption = 3 then 1 else 0 end) as MemosDelay,
  SUM(case when MemosOption = 4 then 1 else 0 end) as MemosCancel,
  Parts.PartsName,
  Count(*) as MemosTotal
from Memos
INNER JOIN Parts ON Parts.PartsId = Memos.PartsId
WHERE date(Memos.MemosCreateDate) BETWEEN '".$starty."' AND '".$endy."'
Group by Memos.PartsId,Parts.PartsName
Order by MemosTotal DESC
";
// echo $sql;
// exit();
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
              <div class="col-md-3 text-right">Select Month</div>
              <div class="col-md-3">
              <select name="year" id="" class='selectpicker show-tick form-control' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php
                  $monthlist = array('2020','2021','2022','2023');

                  foreach ($monthlist as $key => $value) {
                    if ($getdate==$value) {
                      $selected = "selected";
                    }else{
                      $selected = "";
                    }
                    echo "<option value='".$value."' ".$selected.">".$value."</option>";
                  }
                  ?>
              </select>
            </div>
            <div class="col-md-3">
            <button type="submit" class='form-control'>Submit</button>
            </div>
            </div>
        </form>
      

        <!-- <div class="table-responsive">
          <div id="chart_div" style="width: 100%; height: 500px;"></div>
        </div> -->

        </br>
        <div class="row">
        <div class="col-md-12">
        <div id="chart" style="width: 100%; height: 500px;"></div>
        </div>
        <div class="col-md-12">
        <table class='table table-bordered table-sm' id='' width='100%' cellspacing='0'>
                  <thead>
                    <tr>
                      <th rowspan='2' class='text-center align-middle'>Item</th>
                      <th rowspan='2' class='text-center align-middle'>Total</th>
                      <th colspan='12' class='text-center align-middle'><?php echo $getdate ?></th>
                      <th colspan='4' class='text-center align-middle'>Status</th>
                    </tr>
                    <tr>
                    <?php
                      for ($i=1; $i <13 ; $i++) { 
                        $datetext = $getdate."-".$i."-01";
                        echo "<td>".date("M",strtotime($datetext))."</td>";
                      }
                      ?>
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
                  $monthtotal = array();

                  for ($i=1; $i < 13; $i++) { 
                    $datetext = $getdate."-".$i."-01";
                    $monthtotal[date("M",strtotime($datetext))] = 0;
                  }
                  
                  
                  foreach ($report as $key => $value) {
                    $Total += $value['MemosTotal'];
                    $Done += $value['MemosDone'];
                    $Doing += $value['MemosDoing'];
                    $Delay += $value['MemosDelay'];
                    $Cancel += $value['MemosCancel'];
                   ?>
                    <tr class='text-center align-middle'>
                      <td ><?php echo $value['PartsName'] ?></td>
                      <td ><a href="Memoslist.php?part=<?php echo $value['PartsId'] ?>"><?php echo $value['MemosTotal'] ?></a></td>
                      <?php
                      for ($i=1; $i < 13; $i++) { 
                        $datetext = $getdate."-".$i."-01";
                        echo "<td>".$value['Memos'.date("M",strtotime($datetext))]."</td>";
                        $monthtotal[date("M",strtotime($datetext))] += $value['Memos'.date("M",strtotime($datetext))];
                      }
                      
                      ?>
                      <td class='bg-success'><a href="Memoslist.php?part=<?php echo $value['PartsId'] ?>&st=2"><?php echo $value['MemosDone'] ?></a></td>
                      <td class='bg-warning'><a href="Memoslist.php?part=<?php echo $value['PartsId'] ?>&st=1"><?php echo $value['MemosDoing'] ?></a></td>
                      <td class='bg-danger'><a href="Memoslist.php?part=<?php echo $value['PartsId'] ?>&st=3"><?php echo $value['MemosDelay'] ?></a></td>
                      <td><a href="Memoslist.php?part=<?php echo $value['PartsId'] ?>&st=4"><?php echo $value['MemosCancel'] ?></a></td>
                    </tr>
                   <?php
                  }
                  ?>
                    <tr class='text-center align-middle'>
                      <td style='font-weight:bold'><?php echo $oDB->lang('Total') ?></td>
                      <td style='font-weight:bold'><a href="Memoslist.php"><?php echo $Total ?></a></td>
                      <?php
                      for ($i=1; $i < 13; $i++) { 
                        $datetext = $getdate."-".$i."-01";
                        echo "<td><a href='MonthlyReport.php?date=".$datetext."'>".$monthtotal[date("M",strtotime($datetext))]."</a></td>";
                      }
                      
                      ?>
                      <td style='font-weight:bold' class='bg-success'><a href="Memoslist.php?st=2"><?php echo $Done ?></a></td>
                      <td style='font-weight:bold' class='bg-warning'><a href="Memoslist.php?st=1"><?php echo $Doing ?></a></td>
                      <td style='font-weight:bold' class='bg-danger'><a href="Memoslist.php?st=3"><?php echo $Delay ?></a></td>
                      <td style='font-weight:bold'><a href="Memoslist.php?st=4"><?php echo $Cancel ?></a></td>
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

  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    var options = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: true,
                toolbar: {
                    show: true
                },
                zoom: {
                    enabled: true
                }
            },
            //thay màu
            colors: ['#008000', '#FFFF00', '#FF0000', '#808080'],
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                }
            },
            series: [
                      {
                          name: 'Done',
                          data: [<?php
                                    foreach ($report as $key => $value) {
                                      echo "'".$value['MemosDone']."',";
                                    }
                                  ?>]
                      },
                      {
                          name: 'Doing',
                          data: [<?php
                                    foreach ($report as $key => $value) {
                                      echo "'".$value['MemosDoing']."',";
                                    }
                                  ?>]
                      },
                      {
                          name: 'Delay',
                          data: [<?php
                                    foreach ($report as $key => $value) {
                                      echo "'".$value['MemosDelay']."',";
                                    }
                                  ?>]
                      },
                      {
                          name: 'Cancel',
                          data: [<?php
                                    foreach ($report as $key => $value) {
                                      echo "'".$value['MemosCancel']."',";
                                    }
                                  ?>]
                      }
                      ],
            xaxis: {
                type: 'text',
                categories: [
                  <?php
                    foreach ($report as $key => $value) {
                      echo "'".$value['PartsName']."',";
                    }
                  ?>
                ],
            },
            legend: {
                position: 'right',
                offsetY: 40,
            },
            fill: {
                opacity: 1,
                
            },
            dataLabels: {
                        style: {
                          colors: ['#000000']
            }
}


        }

       var chart = new ApexCharts(
            document.querySelector("#chart"),
            options
        );
        
        chart.render();
  </script>

</body>

</html>
