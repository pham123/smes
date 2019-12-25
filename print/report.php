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

        <?php 
          $table_data = $oDB->sl_all('LabelPattern','1');
          $startdate = (isset($_GET['start'])) ? $_GET['start'] : date('Y-m-d') ;
          $enddate = (isset($_GET['end'])) ? $_GET['end'] : date('Y-m-d') ;
          $Productsid = (isset($_GET['Productsid'])) ? $_GET['Productsid'] : '' ;

          if (isset($_GET['Productsid'])||$Productsid!='') {
            $sql = "select SUM(lh.LabelHistoryQuantityOk) as totalOk,
                    SUM( CASE WHEN lh.TraceStationId = 1 THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS DCOK, 
                    SUM( CASE WHEN lh.TraceStationId = 3 THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS NCOK, 
                    SUM( CASE WHEN lh.TraceStationId = 4 THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS STOK, 
                    SUM( CASE WHEN lh.TraceStationId = 5 THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS FIOK, 
                    date(LabelHistoryCreateDate) as crDate,
                    prd.ProductsName,
                    prd.ProductsNumber
                    from LabelHistory lh
                    inner join TraceStation ts on ts.TraceStationId = lh.TraceStationId
                    inner join LabelList lbl on lbl.LabelListValue = lh.LabelHistoryLabelValue
                    inner join Products prd on prd.ProductsId = lbl.ProductsId
                    WHERE date(LabelHistoryCreateDate) between '".$startdate."' AND '".$enddate."' AND prd.ProductsId = ".$Productsid."
                    group by crDate, prd.ProductsName, prd.ProductsNumber
                    ORDER by crDate DESC
                    ";
          } else {
            $sql = "select SUM(lh.LabelHistoryQuantityOk) as totalOk,
                    SUM( CASE WHEN lh.TraceStationId = 1 THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS DCOK, 
                    SUM( CASE WHEN lh.TraceStationId = 3 THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS NCOK, 
                    SUM( CASE WHEN lh.TraceStationId = 4 THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS STOK, 
                    SUM( CASE WHEN lh.TraceStationId = 5 THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS FIOK, 
                    date(LabelHistoryCreateDate) as crDate,
                    prd.ProductsName,
                    prd.ProductsNumber
                    from LabelHistory lh
                    inner join TraceStation ts on ts.TraceStationId = lh.TraceStationId
                    inner join LabelList lbl on lbl.LabelListValue = lh.LabelHistoryLabelValue
                    inner join Products prd on prd.ProductsId = lbl.ProductsId
                    WHERE date(LabelHistoryCreateDate) between '".$startdate."' AND '".$enddate."'
                    group by crDate, prd.ProductsName, prd.ProductsNumber
                    ORDER by crDate DESC
                    ";
          }
          
          

          $result = $oDB->fetchAll($sql);
            // echo "<pre>";
            // var_dump ($result);
            // echo "</pre>";
        ?>
        <div class='row'>
          <form action="" style='width:100%'>
              <div class="row">
              <div class="col-md-3">
              <!-- <span><?php echo $oDB->lang('StartDate') ?></span> -->
              <!-- <input type="date" class='form-control' name='start'> -->
                  <span><?php echo $oDB->lang('Products') ?></span>
                  <select name="Productsid" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                    <?php 
                    $model = $oDB->sl_all('Products',1);
                    foreach ($model as $key => $value) {
                      echo "<option value='".$value['ProductsId']."'>".$value['ProductsNumber']."/".$value['ProductsName']."</option>";
                    }
                    ?>
                    
                  </select>

              
              </div>
              <div class="col-md-3"><span><?php echo $oDB->lang('StartDate') ?></span><input type="date" class='form-control' name='start' value='<?php echo $startdate?>'></div>
              <div class="col-md-3"><span><?php echo $oDB->lang('EndDate') ?></span><input type="date" class='form-control' name='end' value='<?php echo $enddate?>'></div>
              <div class="col-md-3"><span>&nbsp;</span><button type="submit" class='form-control'><?php echo $oDB->lang('Submit') ?></button></div>
              </div>
          </form>
        </div>
        <br>
        <div class="table-responsive">
        <?php
        echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
        echo "<thead>";
        echo "<tr>";
            echo "<th>".$oDB->lang('Index')."</th>";
            echo "<th>".$oDB->lang('Date')."</th>";
            echo "<th>".$oDB->lang('ProductsName')."</th>";
            echo "<th>".$oDB->lang('ProductsNumber')."</th>";
            echo "<th>".$oDB->lang('Dc')."</th>";
            echo "<th>".$oDB->lang('Nc')."</th>";
            echo "<th>".$oDB->lang('St')."</th>";
            echo "<th>".$oDB->lang('Fi')."</th>";
        echo "</tr>";
        echo "</thead>";


        echo "<tbody>";

        foreach ($result as $key => $value) {
            echo "<tr>";
            echo "<td>".($key+1)."</td>";
            echo "<td>".$value['crDate']."</td>";
            echo "<td>".$value['ProductsName']."</td>";
            echo "<td>".$value['ProductsNumber']."</td>";
            echo "<td>".$value['DCOK']."</td>";
            echo "<td>".$value['NCOK']."</td>";
            echo "<td>".$value['STOK']."</td>";
            echo "<td>".$value['FIOK']."</td>";
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
