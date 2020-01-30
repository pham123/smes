<table class='table table-bordered table-sm' id='' width='100%' cellspacing='0'>
                  <thead>
                    <tr style='background-color:#CDCDCD;'>
                      <th rowspan='2' class='text-center align-middle'>Grade</th>
                      <th rowspan='2' class='text-center align-middle'>Evaluated point</th>
                      <th rowspan='2' class='text-center align-middle'>Total</th>
                      <th colspan='4' class='text-center align-middle'>Status</th>
                    </tr>
                    <tr style='background-color:#CDCDCD;'>
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
                    $evaluepoint = '';
                    $namevalue = "";
                    switch ($value['Name']) {
                      case '1':
                        $namevalue = "Best idea";
                        $evaluepoint = "80 or more";
                        break;
                    case '2':
                      $namevalue = "Good idea";
                      $evaluepoint = "50 ~ 79";
                      break;
                    case '3':
                      $namevalue = "Normal idea";
                      $evaluepoint = "< 49";
                      break;
                      default:
                        # code...
                        break;
                    }
                   ?>
                    <tr class='text-center align-middle'>
                      <td><?php echo $namevalue ?></td>
                      <td><?php echo $evaluepoint ?></td>
                      <td><a href="Memoslist.php?<?php echo $get ?>=<?php echo $value['Name'] ?>"><?php echo $value['MemosTotal'] ?></a></td>
                      <td class='bg-success'><a href="Memoslist.php?<?php echo $get ?>=<?php echo $value['Name'] ?>&st=2"><?php echo $value['MemosDone'] ?></a></td>
                      <td class='bg-warning'><a href="Memoslist.php?<?php echo $get ?>=<?php echo $value['Name'] ?>&st=1"><?php echo $value['MemosDoing'] ?></a></td>
                      <td class='bg-danger'><a href="Memoslist.php?<?php echo $get ?>=<?php echo $value['Name'] ?>&st=3"><?php echo $value['MemosDelay'] ?></a></td>
                      <td><a href="Memoslist.php?<?php echo $get ?>=<?php echo $value['Name'] ?>&st=4"><?php echo $value['MemosCancel'] ?></a></td>
                    </tr>
                   <?php
                  }
                  ?>
                    <tr class='text-center align-middle'>
                      <td style='font-weight:bold' colspan='2'><?php echo $oDB->lang('Total') ?></td>
                      <td style='font-weight:bold' ><?php echo $Total ?></td>
                      <td style='font-weight:bold' class='bg-success'><?php echo $Done ?></td>
                      <td style='font-weight:bold' class='bg-warning'><?php echo $Doing ?></td>
                      <td style='font-weight:bold' class='bg-danger'><?php echo $Delay ?></td>
                      <td style='font-weight:bold'><?php echo $Cancel ?></td>
                    </tr>
                  

                  </tbody>
        </table>    

        <div class="col-md-12">
        <div id="chart" style="width: 100%; height: 500px;"></div>
        </div>


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
                      switch ($value['Name']) {
                        case '1':
                          $namevalue = "Best idea";
                          $evaluepoint = "80 or more";
                          break;
                      case '2':
                        $namevalue = "Good idea";
                        $evaluepoint = "50 ~ 79";
                        break;
                      case '3':
                        $namevalue = "Normal idea";
                        $evaluepoint = "< 49";
                        break;
                        default:
                          # code...
                          break;
                      }
                      echo "'".$namevalue."',";
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