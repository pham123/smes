<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/MysqliDb.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$page_css='thead{background-color: #39a0ca;color: white; font-size: 14px;}table th{;vertical-align: middle !important;text-align:center; border-collapse: collapse !important;}.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;}';
require('./template_header.php');
$oDB = new db();
$newDb = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
// $newDB->where('ProductsOption', 4);
?>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary static-top py-0">
    <div class="container">
      <a class="navbar-brand" href="/smes/home"><img src="../img/hallalogo1.png" alt="logo" height="45" >&nbsp;<strong>SPARE PART CONTROL</strong></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Code report
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="patrol.php">Section report</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container-fluid">
    <h5 class="mt-2 text-danger text-center"><strong><i class="fas fa-calendar-alt"></i>&nbsp;MONTHLY REPORT USING BY CODE</strong></h5>
    <hr class="my-0 mb-1">
  <?php   
    $newDb->where('p.ProductsOption', 4);
    // $newDb->join("Exports e", "op.ExportsId=e.ExportsId", "LEFT");
    // $newDb->join("Outputs op", "p.ProductsId=op.ProductsId", "LEFT");
    $spareparts = $newDb->get ("Products p", 50, "p.ProductsId,p.ProductsNumber,p.ProductsName,p.ProductsDescription");
    function calculateQty($pid,$monthVal){
      if($monthVal < 10){
        $month = date('Y').'-0'.$monthVal;
      }else{
        $month = date('Y').'-'.$monthVal;
      }
      global $newDb;
      // $newDb->where('op.ProductsId', $pid);
      // $newDb->join("Exports e", "op.ExportsId=e.ExportsId", "LEFT");
      // $newDb->joinWhere("Exports e", "e.ExportsDate", $month.'-%', 'like');
      // $items = $newDb->get('Outputs op', null, 'op.ProductsQty');
      $items = $newDb->rawQuery('select op.ProductsQty
                                from Outputs op
                                LEFT JOIN Exports ep
                                  ON op.ExportsId = ep.ExportsId
                                  AND ep.ExportsDate like ?
                                WHERE op.ProductsId = ?',[$month.'-%', $pid]);
      $prices = $newDb->rawQuery('select inp.ProductsUnitPrice
                                from Inputs inp
                                LEFT JOIN Imports imp
                                ON imp.ImportsId = inp.ImportsId
                                AND imp.ImportsDate like ?
                                WHERE inp.ProductsId = ?',[$month.'-%',$pid]);
      $sum = 0;
      foreach ($items as $value) {
        $sum += array_sum($value);
      }
      $price = 0;
      foreach($prices as $v){
        $price += array_sum($v);
      }
      return ["qty" => $sum, "price" => $price/count($prices)];

    }
    function calculateAmount($pid,$monthVal){
      return 3000;
    }
    function averageUnitPrice($pid, $monthVal){

    }
  ?>

    <div class="table-responsive">
    <?php
        $monthName = ['1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];
        echo "<table class='table table-bordered table-sm table-striped' id='dataTable' width='100%' cellspacing='0'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th rowspan='2'>Code</th>";
        echo "<th rowspan='2'>Part Name</th>";
        echo "<th rowspan='2'>Spec</th>";
        echo "<th colspan='2'>Total ".date('Y')."</th>";
        for ($i=1; $i <=intval(date('m')) ; $i++) { 
          echo '<th colspan="2">'.$monthName[$i].'-'.date('y').'</th>';
        }
        echo "</tr>";
        echo "<tr>";
        for ($i=1; $i <= intval(date('m'))+1; $i++) { 
          echo '<th>Qty</th>';
          echo '<th>Amount</th>';
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($spareparts as $key => $value) {
          echo "<tr>";
          echo "<td>".$value['ProductsNumber'].'</td>';
          echo "<td>".$value['ProductsName'].'</td>';
          echo "<td>".$value['ProductsDescription'].'</td>';
          echo '<td>'.'total qty'.'</td>';
          echo '<td>'.'total amount'.'</td>';
          for ($j=1; $j <=intval(date('m')) ; $j++) { 
            $temp = calculateQty($value['ProductsId'], $j);
            echo '<td>'.$temp['qty'].'</td>';
            echo '<td>'.number_format($temp['qty'] * $temp['price'], 0, '.',',').'</td>';
          }
          echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        ?>

    </div>

  </div>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script> 
  <script>
    $(function () {
      var data_table = $('#dataTable').DataTable();
      $('select[name=dataTable_length]').addClass('d-inline-block');
      $('select[name=dataTable_length]').css('width','100px');
      $('input[type=search]').addClass('d-inline-block');
      $('input[type=search]').css({'width': '200px', 'margin-left': '10px'});
    });
    </script>

</body>

</html>
