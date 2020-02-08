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
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
?>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary static-top">
    <div class="container">
      <a class="navbar-brand" href="/smes/home"><img src="../img/hallalogo1.png" alt="logo" height="45" >News</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">news list
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="patrol.php">add news</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container-fluid" id="add_patrol">
    <form method="post" action="listen-update-patrol.php" enctype="multipart/form-data">
    <div class="row">
      <div class="col-lg-12 text-center my-2">
        <input type="text" class="form-control" placeholder="enter title ...">
      </div>
      <div class="col-12">
        <textarea name="editor" id="editor" cols="30" rows="500"></textarea>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-6">
        <input type="submit" name="saveBtn" class="btn btn-primary btn-block" value="save">
      </div>
      <div class="col-6">
        <input type="submit" name="sbBtn" class="btn btn-success btn-block" value="submit">
      </div>
    </div>
    </form>
  </div>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/ckeditor/ckeditor.js"></script>

  <script>
    CKEDITOR.plugins.addExternal( 'lineheight', 'plugins/lineheight/', 'plugin.js' );
    CKEDITOR.replace( 'editor', {
      extraPlugins: 'lineheight',
      line_height:"1px;1.1px;1.2px;1.3px;1.4px;1.5px"
    });

  </script>

</body>

</html>
