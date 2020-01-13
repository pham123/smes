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
// $newDB->where('ProductsOption', 4);
?>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary static-top">
    <div class="container">
      <a class="navbar-brand" href="/smes/home"><img src="../img/hallalogo1.png" alt="logo" height="45" >Line Patrol</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">patrol list
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="patrol.php">add patrol</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">top</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">summary</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container-fluid">
  <?php 
        $table_header  = 'id,location,area,item,loss,issue,creator,pic';
        
        $newDb->where('PatrolsStatus', 1);
        $newDb->join("PatrolItems pi", "pt.PatrolItemsId=pi.PatrolItemsId", "LEFT");
        $newDb->join("PatrolLosses pl", "pt.PatrolLossesId=pl.PatrolLossesId", "LEFT");
        $newDb->join("Users u", "pt.PatrolsCreator=u.UsersId", "LEFT");
        $newDb->join("Users u2", "pt.UsersId=u2.UsersId", "LEFT");
        $newDb->join("Areas a", "pt.AreasId=a.AreasId", "LEFT");
        $table_data = $newDb->get ("Patrols pt", null, "pt.PatrolsId as id,pt.PatrolsLocation as location,a.AreasName as area,pi.PatrolItemsName as item,pl.PatrolLossesName as loss,pt.PatrolsContent as issue,u.UsersFullName as creator,u2.UsersFullName as pic");
        $table_link = "editsparepart.php?id=";
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

</body>

</html>