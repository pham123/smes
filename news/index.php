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
$pagetitle = $user->module;
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;}';
require('./template_header.php');
$oDB = new db();
$newDb = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
?>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary static-top py-0">
    <div class="container">
      <a class="navbar-brand" href="/smes/home"><img src="../img/hallalogo1.png" alt="logo" height="45" >&nbsp;<strong>Line Patrol</strong></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">news list
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="news.php">add news</a>
          </li>
          <span id="country_selector"></span>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container-fluid">
  <?php 
        $table_header  = 'id,location,area,item,loss,issue,creator,pic,picture';
        
        $newDb->where('PatrolsStatus', 1);
        $newDb->join("PatrolItems pi", "pt.PatrolItemsId=pi.PatrolItemsId", "LEFT");
        $newDb->join("PatrolLosses pl", "pt.PatrolLossesId=pl.PatrolLossesId", "LEFT");
        $newDb->join("Users u", "pt.PatrolsCreator=u.UsersId", "LEFT");
        $newDb->join("Users u2", "pt.UsersId=u2.UsersId", "LEFT");
        $newDb->join("Areas a", "pt.AreasId=a.AreasId", "LEFT");
        $table_data = $newDb->get ("Patrols pt", null, "pt.PatrolsId as id,pt.PatrolsLocation as location,a.AreasName as area,pi.PatrolItemsName as item,pl.PatrolLossesName as loss,pt.PatrolsContent as issue,u.UsersFullName as creator,u2.UsersFullName as pic, CONCAT('<img src=\'image/small/',pt.PatrolsId, '.jpg\' style=\'max-height: 45px;\'/>') AS picture");
        $table_link = "editpatrol.php?id=";
        ?>

    <div class="table-responsive">
    <table border="0" cellspacing="3" cellpadding="3" class="display nowrap my-2">
        <tbody><tr>
            <td style="padding:1px 0;">item:</td>
            <td style="padding:1px 5;">
                <select id="item_filter" class="form-control" style="max-width: 150px;">
                <?php 
                $items = $oDB->sl_all('PatrolItems',1);
                echo "<option value=''>all items</option>";
                foreach ($items as $key => $value) {
                    echo "<option value='".$value['PatrolItemsName']."'>".$value['PatrolItemsName']."</option>";
                }
                ?>
                
                </select>
            </td>
            <td style="padding:1px 0;">area:</td>
            <td style="padding:1px 5px;">
                <select id="area_filter" class="form-control" style="max-width: 150px;">
                    <?php 
                    $areas = $oDB->sl_all('Areas',1);
                    echo "<option value=''>all area</option>";
                    foreach ($areas as $key => $value) {
                        echo "<option value='".$value['AreasName']."'>".$value['AreasName']."</option>";
                    }
                    ?>
                
                </select>
            </td>
            <td style="padding:1px 0;">loss:</td>
            <td style="padding:1px 5px;">
                <select id="loss_filter" class="form-control" style="max-width: 150px;">
                    <?php 
                    $losses = $oDB->sl_all('PatrolLosses',1);
                    echo "<option value=''>all losses</option>";
                    foreach ($losses as $key => $value) {
                        echo "<option value='".$value['PatrolLossesName']."'>".$value['PatrolLossesName']."</option>";
                    }
                    ?>
                
                </select>
            </td>
        </tr>
    </tbody>
    </table>
    <?php
        $tablearr = explode(',',$table_header);
        echo "<table class='table table-bordered table-sm' id='dataTable' width='100%' cellspacing='0'>";
        echo "<thead>";
        echo "<tr>";
        foreach ($tablearr as $key => $value) {
            echo "<th>".$oDB->lang($value)."</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($table_data as $key => $value) {

            echo "<tr>";
            foreach ($tablearr as $key2 => $value2) {
                if ($key2==0) {
                        echo "<td><a href='".$table_link.$value['id']."'>".$value[$value2]."</a></td>";
                }else{
                    echo "<td>".$value[$value2]."</td>";
                }
            
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
  <script src="../vendor/country-picker-flags/js/countrySelect.js"></script>
  <script>
    $.fn.dataTable.ext.search.push(
      function( settings, data, dataIndex ) {
          let i_filter = $('#item_filter').val();
          let l_filter = $('#loss_filter').val();
          let a_filter = $('#area_filter').val();
          let i_value = data[3];
          let l_value = data[4];
          let a_value = data[2];

          return i_value.includes(i_filter) && l_value.includes(l_filter) && a_value.includes(a_filter);
      }
    );
    $(function () {
      var data_table = $('#dataTable').DataTable();
      $('#item_filter').change(function(){
        data_table.draw();
      });
      $('#loss_filter').change(function(){
        data_table.draw();
      });
      $('#area_filter').change(function(){
        data_table.draw();
      });
    });

     $(document).on('test', function(e,code){
      $.ajax({
        url: 'ajaxupdatelang.php?code='+code,
        type: 'get',
        success: function(){
          location.reload(true);
        }
      })
    });

    $("#country_selector").countrySelect({
      onlyCountries: ['en','vi','kr','cn'],
      preferredCountries: []
    });
    $('.country-list').css('overflow','hidden');
    $("#country_selector").countrySelect("selectCountry",<?php echo json_encode($oDB->lang); ?>);

  </script>

</body>

</html>
