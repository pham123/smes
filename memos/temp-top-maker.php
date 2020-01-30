<table class='table table-bordered table-sm' id='' width='100%' cellspacing='0'>
                  <thead>
                    <tr style='background-color:#CDCDCD;'>
                      <th rowspan='2' class='text-center align-middle'>Name</th>
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
                   ?>
                    <tr class='text-center align-middle'>
                      <td><?php echo $value['Name'] ?></td>
                      <td><a href="Memoslist.php?<?php echo $get ?>=<?php echo $value['Id'] ?>"><?php echo $value['MemosTotal'] ?></a></td>
                      <td class='bg-success'><a href="Memoslist.php?<?php echo $get ?>=<?php echo $value['Id'] ?>&st=2"><?php echo $value['MemosDone'] ?></a></td>
                      <td class='bg-warning'><a href="Memoslist.php?<?php echo $get ?>=<?php echo $value['Id'] ?>&st=1"><?php echo $value['MemosDoing'] ?></a></td>
                      <td class='bg-danger'><a href="Memoslist.php?<?php echo $get ?>=<?php echo $value['Id'] ?>&st=3"><?php echo $value['MemosDelay'] ?></a></td>
                      <td><a href="Memoslist.php?<?php echo $get ?>=<?php echo $value['Id'] ?>&st=4"><?php echo $value['MemosCancel'] ?></a></td>
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