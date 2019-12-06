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
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> -->
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
          ['Status', 'Quantity'],
          ['Done',  11],
          ['Doing',  2],
          ['Delay',  2],
          ['Cancel', 2]
        ]);

        var options = {
          title: 'Quality issue control rate',
          pieHole: 0.4,
          slices: [{color: 'Green'}, {color:'yellow'}, {color: 'red'}, {color: 'grey'}]
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
        
      }

      
    </script>

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
        <div class="table-responsive">
          <div id="chart_div" style="width: 100%; height: 500px;"></div>
        </div>


        <div class="table-responsive">
          <div id="chart" style="width: 100%; height: 500px;"></div>
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
                          data: [44, 55, 41, 67, 22, 43]
                      },
                      {
                          name: 'Doing',
                          data: [13, 23, 20, 8, 13, 27]
                      },
                      {
                          name: 'Delay',
                          data: [11, 17, 15, 15, 21, 14]
                      },
                      {
                          name: 'Cancel',
                          data: [1, 2, 2, 1, 1, 2]
                      }
                      ],
            xaxis: {
                type: 'text',
                categories: ['DC', 'LG-IVI', 'NC', 'LG-WM', 'ST', 'FI'],
            },
            legend: {
                position: 'right',
                offsetY: 40,
            },
            fill: {
                opacity: 1,
                
            },


        }

       var chart = new ApexCharts(
            document.querySelector("#chart"),
            options
        );
        
        chart.render();
  </script>

</body>

</html>
