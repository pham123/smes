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

// $actionar = (array_keys($_GET));
// $actionkey = (isset($actionar[0])) ? $actionar[0] : 'content' ;

// $action =  (explode("_",$actionkey));

// $option = $action[0];
// $target = (isset($action[1])) ? ucfirst($action[1]) : 'Company' ;
// $id = (isset($action[2])) ? $action[2] : 1 ;

// if (file_exists('../querry/'.$option.'_'.$target.'.php')) {
//   require('../querry/'.$option.'_'.$target.'.php');
// }else {
//   $sql = "Select * from ".$target;
// }




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
        <!-- <form action="labeltrace.php" >
          <input type="text" name="code" id="" class='form-control' autofocus>
        </form> -->

        <?php 
        if (isset($_GET['code'])&&safe($_GET['code'])!="") {
          # code...
          $code = safe($_GET['code']);
        }else{
          exit();
          
        }
        //Lấy về danh sách DC

        $sql = "SELECT lbl.`LabelListId` 
        FROM `labellist` lbl
        inner JOIN `labelhistory` lh on lh.`LabelHistoryLabelValue` = lbl.`LabelListValue`
        WHERE date(`LabelListCreateDate`) = (SELECT date(`LabelListCreateDate`) FROM `labellist` WHERE `LabelListValue` = '".$code."') 
        AND lh.`TraceStationId` = 1
        AND lbl.`ProductsId` = (SELECT date(`ProductsId`) FROM `labellist` WHERE `LabelListValue` = '".$code."')
        ";

        $dcarr = $oDB -> fetchAll($sql);
        $list = array();
        var_dump($dcarr);
        foreach ($dcarr as $key => $value) {
          $list[]=$value['LabelListId'];
        }

        $dctext = implode(',',$list);
        
        $textarr = array();
        $newarr = array();
        $newarr[0] = $list;
        $j=0;
        for ($i=0; $i < 2 ; $i++) { 
          $j = $i+1;
          $text_pre = implode(',',$newarr[$i]);
          $sql2 = "SELECT lbl.`LabelListId` 
                      FROM `labellist` lbl
                      WHERE lbl.`LabelListMotherId` in (".$text_pre.")";
          $arr= $oDB -> fetchAll($sql2);
          
          // var_dump($arr);
          if (isset($arr[0]['LabelListId'])&&$arr[0]['LabelListId']!="") {
            foreach ($arr as $key => $value) {
              $newarr[$j][] = $value['LabelListId'];
              $list[] = $value['LabelListId'];
            }
          }else{
            break;
          }
        }
        array_unique($list);
        $text = implode(',',$list);
        $sql = "select lh.*,prd.ProductsName,prd.ProductsNumber,ts.TraceStationName,lbl.LabelListId as DcLabelId from LabelHistory lh
        inner join LabelList lbl on lbl.LabelListValue = lh.LabelHistoryLabelValue
        inner join TraceStation ts on ts.TraceStationId = lh.TraceStationId
        inner join Products prd on prd.ProductsId = lbl.ProductsId
        WHERE lbl.LabelListId in (".$text.")
        ORDER BY lh.LabelHistoryCreateDate DESC";
        
        $result = $oDB->fetchAll($sql);
        // var_dump($result);
        // exit();
        ?>
        <div class="table-responsive">
        <?php
        echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
        echo "<thead>";
        echo "<tr>";
            echo "<th>".$oDB->lang('Index')."</th>";
            echo "<th>".$oDB->lang('Station')."</th>";
            echo "<th>".$oDB->lang('ProductName')."</th>";
            echo "<th>".$oDB->lang('ProductNumber')."</th>";
            echo "<th>".$oDB->lang('Ok')."</th>";
            echo "<th>".$oDB->lang('Ng')."</th>";
            echo "<th>".$oDB->lang('LabelValue')."</th>";
            echo "<th>".$oDB->lang('IssueDate')."</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($result as $key => $value) {
            echo "<tr>";
            echo "<td>".($key+1)."</td>";
            echo "<td>".$value['TraceStationName']."</td>";
            echo "<td>".$value['ProductsName']."</td>";
            echo "<td>".$value['ProductsNumber']."</td>";
            echo "<td style='background-color:#73E700;'>".$value['LabelHistoryQuantityOk']."</td>";
            echo "<td style='background-color:red;'>".$value['LabelHistoryQuantityNg']."</td>";
            echo "<td>".$value['LabelHistoryLabelValue']."</td>";
            echo "<td>".$value['LabelHistoryCreateDate']."</td>";
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
