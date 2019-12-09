<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
$oDB = new db();
$oDB->lang = 'En';
//echo $currentlocation;
// var_dump($_SESSION);
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

  <title><?php $oDB->lang('HallaElectronicsVina') ?></title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="" Style='background-color:white;'>
        <div class="sidebar-brand-icon rotate-n-15">
          <img src="../img/hallalogo.png" alt="logo" height="45" >
        </div>
        <div class="sidebar-brand-text mx-3" Style='Color:#22356f;font-size: 3em'>HEV</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="index.html">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Trang chủ</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Thông tin
      </div>
      <!-- Nav Item - Charts -->
      <li class="nav-item">
          <a class="nav-link" href="#">
          <i class="fas fa-fw fa-calendar-alt"></i>
          <span><?php echo date('Y-m-d H:i') ?></span></a>
      </li>

      <!-- Nav Item - Tables -->
      <!-- <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-dollar-sign"></i>
          <span>23,234.20 VNĐ</span></a>
      </li>

      <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="fas fa-fw fa-won-sign"></i>
            <span>19.25 VNĐ</span></a>
        </li> -->

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION[_site_]['userfullname']?></span>
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
                <a class="dropdown-item" href="/user/profile.php">
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
  array('#',$oDB->lang('Productivity','Sản lượng'),$oDB->lang('Quantity'),'fa-pallet'),
  array('../print/',$oDB->lang('Print'),$oDB->lang('PrintLabel'),'fa-barcode'),
  array('../products/',$oDB->lang('Products'),'Part Name, Part No','fa-dolly'),
  array('#',$oDB->lang('LinePatrol','Báo cáo'),$oDB->lang('LinePatrol','Báo cáo'),'fa-camera'),
  array('../quality/',$oDB->lang('Quality'),$oDB->lang('QualityIssueControl'),'fa-bullhorn'),
  // array('#',$oDB->lang('QulityList'),'Push QA issues alert','fa-list-ol'),
  array('#','SPARE PART','Control spare part','fa-boxes'),
  array('#','Tài liệu','BOM, ISO, PFMEA','fa-folder-open'),
  array('#','Nhân viên','Information','fa-calendar'),

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
              <!-- Illustrations -->
              <div class="card shadow mb-4">
              <!-- Approach -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Thông báo</h6>
                </div>
                <div class="card-body">
                  <h1>Thử nghiệm hệ thống nhập liệu sản lượng trên chuyền sản xuất</h1>
                </div>
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
  <!-- Page level custom scripts -->
  <script src="../js/demo/chart-area-demo.js"></script>
  <script src="../js/demo/chart-pie-demo.js"></script>

</body>

</html>




