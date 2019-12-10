<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
$oDB = new db();
$oDB->lang = 'En';
//echo $currentlocation;
// var_dump($_SESSION[_site_]['userid']);
if(!isset($_SESSION[_site_])){
    header('Location: ../login.php');
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

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../home/index.php" Style='text-decoration: none; background-color:white;'>
        <div class="sidebar-brand-icon rotate-n-15">
          <img src="../img/hallalogo.png" alt="logo" height="45" >
        </div>
        <div class="sidebar-brand-text mx-3" Style='Color:#22356f;font-size: 3em'>HEV</div>
      </a>
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
                <div class="col-8 mx-auto pb-4">
                    <h4 class="text-center">UPDATE USER INFORMATION</h4>
                    <form method="post" action="listen-update-profile.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="UsersFullName">Fullname</label>
                            <input type="text" class="form-control" name="UsersFullName" id="UsersFullName" required value="<?php echo $_SESSION[_site_]['userfullname']?>">
                        </div>
                        <div class="form-group">
                            <label for="UsersEmail">Email</label>
                            <input type="email" class="form-control" name="UsersEmail" id="UsersEmail" required value="<?php echo $_SESSION[_site_]['useremail']?>">
                        </div>
                        <div class="form-group">
                            <label for="UsersPassword">New Password</label>
                            <input type="password" class="form-control" name="UsersPassword" id="UsersPassword" placeholder="New Password">
                            <small class="text-muted">(Để trống nếu bạn không muốn thay đổi!)</small>
                        </div>
                        <div class="form-group">
                            <label for="UsersPicture">Picture</label>
                            <input type="file" name="UsersPicture" id="UsersPicture" accept="image/jpeg">
                            <br>
                            <img id="origin_pic" style="max-height: 250px;" src="./image/user_<?php echo $_SESSION[_site_]['userid'] ?>.jpg" alt="">
                            <img id="preview" style="max-height: 250px;" class="d-none" alt="">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        <!-- /.container-fluid -->
        </div>
      <!-- End of Main Content -->
      <!-- Footer -->
        <footer class="sticky-footer bg-dark text-white">
            <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Halla Electronics Vina 2019</span>
            </div>
            </div>
        </footer>
      <!-- End of Footer -->
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
  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>
  <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
            }
        }

        $("#UsersPicture").change(function() {
            $('#preview').removeClass('d-none');
            $('#origin_pic').addClass('d-none');
            readURL(this);
        });
  </script>

</body>

</html>




