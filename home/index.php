<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
$oDB = new db();
$user = new Users();

if (!isset($_SESSION[_site_]['userid'])) {
  # code...
  header('Location:../login.php');
  exit();
}else{
  $user->set($_SESSION[_site_]['userid']);
}
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}else{
  $_SESSION[_site_]['userlang'] = $user->lang;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="../img/halla.png" />
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $oDB->lang('HallaElectronicsVina'); ?></title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/country-picker-flags/css/countrySelect.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-primary text-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <div class="navbar-brand rotate-n-15">
            <img src="../img/hallalogo1.png" alt="logo" height="45" > <strong class="text-white">Halla Electronics Vina</strong>
        </div>

          <!-- Topbar Search -->
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto text-white">
            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              
            </li>

            <!-- Nav Item - User Information -->
            <span id="country_selector"></span>
            <li class="nav-item dropdown no-arrow" style="margin-left: 40px;">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-white small"><?php echo $_SESSION[_site_]['userfullname']?></span>
                <?php
                if(!file_exists('../user/image/user_'.$_SESSION[_site_]['userid'].'.jpg')){
                ?>
                  <img class="img-profile rounded-circle" style="object-fit: cover" src="../img/Users/1.png">
                <?php
                }else{
                ?>
                  <img class="img-profile rounded-circle" style="object-fit: cover" src="../user/image/user_<?php echo $_SESSION[_site_]['userid']?>.jpg">
                <?php
                }
                ?>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="../user/profile.php">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
        <!-- Content Row -->
        <div class="row">
<?php

$linkar =  array(
  // array('../Approval/','APPROVAL','Request everything!','fa-check-square'),
  array('../Productivity/',$oDB->lang('Productivity','Sản lượng'),$oDB->lang('Quantity'),'fa-pallet'),
  array('../print/',$oDB->lang('Print'),$oDB->lang('PrintLabel'),'fa-barcode'),
  array('../products/',$oDB->lang('Products'),$oDB->lang('ProductsInformation'),'fa-dolly'),
  array('../patrols',$oDB->lang('LinePatrol'),$oDB->lang('FactoryIssueReport'),'fa-camera'),
  array('../quality/',$oDB->lang('Qbank',"Q-Bank"),$oDB->lang('QualityIssueControl'),'fa-bullhorn'),
  // array('#',$oDB->lang('QulityList'),'Push QA issues alert','fa-list-ol'),
  array('../spare-part',$oDB->lang('SparePart'),$oDB->lang('ControlSparePart'),'fa-boxes'),
  array('#',$oDB->lang('Document',"Document"),$oDB->lang('DocumentEx'),'fa-folder-open'),
  array('../memos/',$oDB->lang('MemoSuggestion'),$oDB->lang('MemoSuggestion'),'fa-lightbulb'),
  array('../employees/',$oDB->lang('Employees'),$oDB->lang('EmployeesInformation'),'fa-calendar'),

);

if ($_SESSION[_site_]['useroption']==1) {
  $linkar[]=  array('../system/','ADMIN','Admin System','fa-cogs');
}

foreach ($linkar as $key => $value) {
  ?>
              <div class="col-xl-3 col-md-6 mb-4">
                <a href="<?php echo $value[0] ?>">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $value[1] ?></div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><?php echo $value[2] ?></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas <?php echo $value[3] ?> fa-3x" style='color:#D2322D;'></i>
                      </div>
                    </div>
                  </div>
                </div>
                </a>
              </div>
  <?php
}
?>
            </div>
          <!-- Content Row -->
          <!-- Content Row -->
          <div class="row">
              <div class="card shadow mb-4 w-100">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Thông báo</h6>
                </div>
                <div class="card-body">
                  <h4>Thử nghiệm hệ thống nhập liệu sản lượng trên chuyền sản xuất</h4>
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
            <span>Copyright &copy; Halla Electronics Vina 2019</span>
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
          <a class="btn btn-primary" href="../login.php">Logout</a>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>
  <!-- Page level plugins -->
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="../vendor/country-picker-flags/js/countrySelect.js"></script>
  <!-- Page level custom scripts -->
  <script src="../js/demo/chart-area-demo.js"></script>
  <script src="../js/demo/chart-pie-demo.js"></script>

  <script>
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




