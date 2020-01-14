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
$page_css = 'p{margin-bottom: 0px;}.col-md-6{padding-bottom: 10px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();

$patrol = $oDB->sl_one('Patrols', 'PatrolsId = '.$_GET['id']);
?>

</style>
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

  <!-- Begin Page Content -->
  <div class="container-fluid">
  <div class="">
    <form action="listen-update-patrol.php" method="post" enctype="multipart/form-data">
      <h3 class="text-center my-2 py-1 bg-secondary text-white">UPDATE PATROL</h3>
      <div class="row">
          <div class="col-md-6">
            <p><?php echo $oDB->lang('Item') ?></p>
            <select name="PatrolItemsId" id="" class="form-control">
              <?php 
              $pis = $oDB->sl_all('PatrolItems',1);
              echo "<option value=''>select item</option>";
              foreach ($pis as $key => $value) {
                if($value['PatrolItemsId'] == $patrol['PatrolItemsId']){
                  echo "<option selected value='".$value['PatrolItemsId']."'>".$value['PatrolItemsName']."</option>";
                }else{
                  echo "<option value='".$value['PatrolItemsId']."'>".$value['PatrolItemsName']."</option>";
                }
              }
              ?>
              
            </select>
            <input type="hidden" name="PatrolsId" value="<?php echo $patrol['PatrolsId']?>">
          </div>
          <div class="col-md-6">
            <p><?php echo $oDB->lang('Area') ?></p>
            <select name="AreasId" id="" class="form-control">
              <?php 
              $areas = $oDB->sl_all('Areas',1);
              echo "<option value=''>select area</option>";
              foreach ($areas as $key => $value) {
                if($value['AreasId'] == $patrol['AreasId']){
                  echo "<option selected value='".$value['AreasId']."'>".$value['AreasName']."</option>";
                }else{
                  echo "<option value='".$value['AreasId']."'>".$value['AreasName']."</option>";
                }
              }
              ?>
              
            </select>
          </div>
          <div class="col-md-6">
            <p><?php echo $oDB->lang('Loss') ?></p>
            <select name="PatrolLossesId" id="" class="form-control">
              <?php 
              $losses = $oDB->sl_all('PatrolLosses',1);
              echo "<option value=''>select loss</option>";
              foreach ($losses as $key => $value) {
                if($value['PatrolLossesId'] == $patrol['PatrolLossesId']){
                  echo "<option selected value='".$value['PatrolLossesId']."'>".$value['PatrolLossesName']."</option>";
                }else{
                  echo "<option value='".$value['PatrolLossesId']."'>".$value['PatrolLossesName']."</option>";
                }
              }
              ?>
              
            </select>
          </div>
          <div class="col-md-6">
            <p><?php echo $oDB->lang('Location') ?></p>
            <input type="text" name="PatrolsLocation" id="" class='form-control' value="<?php echo $patrol['PatrolsLocation'] ?>">
          </div>
          <div class="col-md-6">
            <p><?php echo $oDB->lang('Issue') ?></p>
            <textarea name="PatrolsContent" class="form-control"><?php echo $patrol['PatrolsContent'] ?></textarea>
          </div>

          <div class="col-md-6">
            <p><?php echo $oDB->lang('EditPicture', 'Edit Picture') ?></p>
            <input type="file" name='fileToUpload' class="form-control" >
            <br>  
            <img style="max-height: 270px;" src="./image/<?php echo $patrol['PatrolsId'] ?>.jpg" alt="">
          </div>

          <div class="col-md-6">
            <br>
            <button type="submit" class='btn btn-primary btn-block'><?php echo $oDB->lang('Submit') ?></button>
          </div>

        </div>
    </form>
  </div>
  </div>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
