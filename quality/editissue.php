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
$access = $user->acess();
$pagetitle = $user->module;
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
if (is_numeric($_GET['id'])) {
  $id = safe($_GET['id']) ;
  $_SESSION[_site_]['editissueid']=$id;
}else{
  header ('Location: index.php');
  exit();
}
$issue = $oDB->sl_One('QualityIssuelist','QualityIssuelistId='.$id);
// var_dump($issue);

// echo $oDB->getcol('qualityissuelist');

?>
<!-- <meta http-equiv="refresh" content="30"> -->
<style>
p{margin:5px;}

<style>
  .ck-editor__editable {
      min-height: 400px;
  }

  #progress-wrp {
  border: 1px solid #0099CC;
  padding: 1px;
  position: relative;
  height: 30px;
  border-radius: 3px;
  margin: 10px;
  text-align: left;
  background: #fff;
  box-shadow: inset 1px 3px 6px rgba(0, 0, 0, 0.12);
}

#progress-wrp .progress-bar {
  height: 100%;
  border-radius: 3px;
  background-color: #f39ac7;
  width: 0;
  box-shadow: inset 1px 1px 10px rgba(0, 0, 0, 0.11);
}

#progress-wrp .status {
  top: 3px;
  left: 50%;
  position: absolute;
  display: inline-block;
  color: #000000;
}
</style>
</style>
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
        <div class="">
          <form action="#" method="post" enctype="multipart/form-data">
              <input type="hidden" id="QualityIssuelistId" value="<?php echo $issue['QualityIssuelistId']?>">
              <div class="row">
                <div class="col-md-12">
                  <p><?php echo $oDB->lang('Title') ?></p>
                  <input type="text" name="QualityIssuelistTitle" id="" class='form-control' required value="<?php echo $issue['QualityIssuelistTitle'] ?>">
                </div>

                <div class="col-md-12">
                  <p><?php echo $oDB->lang('DefectContent') ?></p>
                  <textarea name="QualityIssuelistDefectiveContent" id="" class="form-control" rows="3"><?php echo $issue['QualityIssuelistDefectiveContent'] ?></textarea>
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('IssueDate') ?></p>
                  <input type="date" name="QualityIssuelistDate" id="" class='form-control' value="<?php echo $issue['QualityIssuelistDate'] ?>" readonly>
                </div>

                <div class="col-md-2">
                <p><?php echo $oDB->lang('ScmObject') ?></p>
                <select name="SupplyChainObjectId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('SupplyChainObject',1);
                  foreach ($model as $key => $value) {
                    $selected = ($value['SupplyChainObjectId']==$issue['SupplyChainObjectId']) ? 'selected' : '' ;
                    echo "<option value='".$value['SupplyChainObjectId']."' ".$selected.">".$value['SupplyChainObjectName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <div class="col-md-3">
                <p><?php echo $oDB->lang('Products') ?></p>
                <select name="ProductsId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('Products','ProductsOption=1');
                  foreach ($model as $key => $value) {
                    $selected = ($value['ProductsId']==$issue['ProductsId']) ? 'selected' : '' ;
                    echo "<option value='".$value['ProductsId']."' ".$selected.">".$value['ProductsName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <input type="hidden" name="QualityIssuelistCreator" value="<?php echo $_SESSION[_site_]['userid'] ?>">

                <div class="col-md-4">
                  <p><?php echo $oDB->lang('LotNo') ?></p>
                  <input type="text" name="QualityIssuelistLotNo" id="" class='form-control' value="<?php echo $issue['QualityIssuelistLotNo'] ?>">
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('ProductionDate') ?></p>
                  <input type="date" name="QualityIssuelistProductionDate" id="" class='form-control' value="<?php echo $issue['QualityIssuelistProductionDate'] ?>">
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('LotQuantity') ?></p>
                  <input type="number" name="QualityIssuelistLotQuantity" id="" class='form-control' value="<?php echo $issue['QualityIssuelistLotQuantity'] ?>">
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('NgQuantity') ?></p>
                  <input type="number" name="QualityIssuelistNgQuantity" id="" class='form-control' value="<?php echo $issue['QualityIssuelistNgQuantity'] ?>">
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('TimeOccurs') ?></p>
                  <input type="number" name="QualityIssuelistTimesOccurs" id="" class='form-control' value="<?php echo $issue['QualityIssuelistTimesOccurs'] ?>">
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('DocNo') ?></p>
                  <input type="text" name="QualityIssuelistDocNo" id="" class='form-control' value="<?php echo $issue['QualityIssuelistDocNo'] ?>">
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('DueDate') ?></p>
                  <input type="date" name="QualityIssuelistDueDate" id="" class='form-control' value="<?php echo $issue['QualityIssuelistDueDate'] ?>">
                </div>

                <div class="col-md-3">
                  <p><?php echo $oDB->lang('FinishDate') ?></p>
                  <input type="date" name="QualityIssuelistFinishDate" id="" class='form-control' value="<?php echo $issue['QualityIssuelistFinishDate'] ?>">
                </div>

                <div class="col-md-3">
                <p><?php echo $oDB->lang('Pic') ?></p>
                <select name="UsersId" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                  <?php 
                  $model = $oDB->sl_all('Users',1);
                  foreach ($model as $key => $value) {
                    $selected = ($value['UsersId']==$issue['UsersId']) ? 'selected' : '' ;
                    echo "<option value='".$value['UsersId']."' ".$selected.">".$value['UsersName']."</option>";
                  }
                  ?>
                </select>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('RootCause') ?></p>
                  <textarea name="QualityIssuelistRootCause" id="" class="form-control" rows="3"><?php echo $issue['QualityIssuelistRootCause'] ?></textarea>
                </div>

                <div class="col-md-6">
                  <p><?php echo $oDB->lang('Action') ?></p>
                  <textarea name="QualityIssuelistAction" id="" class="form-control" rows="3"><?php echo $issue['QualityIssuelistAction'] ?></textarea>
                </div>

                <!-- <div class="col-md-3">
                  <p><?php echo $oDB->lang('IssuePicture') ?></p>
                  <input type="file" id='ingredient_file' name='issuepicture' class="form-control" >  
                </div> -->

                <div class="col-md-3">
                  <p style='margin:0px;'><?php echo $oDB->lang('IssuePicture') ?></p>
                  <input type="file" id='issuepicture' class="form-control" accept=".JPG,.PNG,.jpg,.png">  
                  <!-- <div id="progress-wrp">
                      <div class="progress-bar"></div>
                      <div class="status">0%</div>
                  </div> -->
                </div>

                <!-- <div class="col-md-3">
                  <p><?php echo $oDB->lang('IssueReport') ?></p>
                  <input type="file" id='ingredient_file' name='issuereport' class="form-control" >  
                </div> -->

                <div class="col-md-3">
                  <p style='margin:0px;'>File</p>
                  <input type="file" id='IssueReport' class="form-control" accept=".pptx">  
                  <!-- <div id="progress-wrp-IssueReport">
                      <div class="progress-bar-IssueReport"></div>
                      <div class="status">0%</div>
                  </div> -->
                </div>

                
                <div class="col-md-3">
                  <!-- <p style='margin:0px;'>File</p>
                  <input type="file" id='IssueReport' class="form-control">   -->
                  <p>&nbsp;</p>
                  <div id="progress-wrp">
                      <div class="progress-bar"></div>
                      <div class="status">0%</div>
                  </div>
                </div>
<?php
if ($access==1||$access==2) {
  # code...
?>
                <div class="col-md-3">
                <p><?php echo $oDB->lang('Status') ?></p>
                <select name="QualityIssuelistStatus" id="" class='selectpicker show-tick' data-live-search="true" data-style="btn-info" data-width="100%">
                    <option value="1" <?php echo $selected = ($issue['QualityIssuelistStatus']==1) ? 'selected' : '' ;?>>Doing</option>
                    <option value="2" <?php echo $selected = ($issue['QualityIssuelistStatus']==2) ? 'selected' : '' ;?>>Done</option>
                    <option value="3" <?php echo $selected = ($issue['QualityIssuelistStatus']==3) ? 'selected' : '' ;?>>Delay</option>
                    <option value="4" <?php echo $selected = ($issue['QualityIssuelistStatus']==4) ? 'selected' : '' ;?>>Cancel</option>
                </select>
                </div>
<?php
}
?>
<!-- 
                <div class="col-md-3">
                  <p>&nbsp;</p>
                  <button type="submit" class='btn btn-primary btn-block'><?php echo $oDB->lang('Submit') ?></button>
                </div> -->

              </div>
          </form>
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

    $(function () {
    $("input").on("change", function() {
    //alert($(this).val());
    
    var name = $(this).attr("name");
    var value = $(this).val();
    var id = $('#QualityIssuelistId').val();

    //alert(id);

        $.ajax({
            type: "POST",
            url: "aj-listen-edit-issue.php",
            cache: false,
            data: {name:name,value:value,id:id}
            }).done(function( result ) {
            // var kq = result;
            // alert(kq);
            // $('.ketqua').html(kq);
            //location.reload();
        }); 
    });
    });

    $(function () {
    $("textarea").on("change", function() {
    //alert($(this).val());
    
    var name = $(this).attr("name");
    var value = $(this).val();
    var id = $('#QualityIssuelistId').val();

    //alert(id);

        $.ajax({
            type: "POST",
            url: "aj-listen-edit-issue.php",
            cache: false,
            data: {name:name,value:value,id:id}
            }).done(function( result ) {
            // var kq = result;
            // alert(kq);
            // $('.ketqua').html(kq);
            //location.reload();
        }); 
    });
    });


    $(function () {
    $("select").on("change", function() {
    //alert($(this).val());
    
    var name = $(this).attr("name");
    var value = $(this).val();
    var id = $('#QualityIssuelistId').val();

    //alert(id);

        $.ajax({
            type: "POST",
            url: "aj-listen-edit-issue.php",
            cache: false,
            data: {name:name,value:value,id:id}
            }).done(function( result ) {
            // var kq = result;
            // alert(kq);
            // $('.ketqua').html(kq);
            //location.reload();
        }); 
    });
    });



//upload file

//Phần này dùng để xử lý upload file lên
var Upload = function (file) {
    this.file = file;
};

Upload.prototype.getType = function() {
    return this.file.type;
};
Upload.prototype.getSize = function() {
    return this.file.size;
};
Upload.prototype.getName = function() {
    return this.file.name;
};
Upload.prototype.doUpload = function (link) {
    var that = this;
    var formData = new FormData();

    // add assoc key values, this will be posts values
    formData.append("file", this.file, this.getName());
    formData.append("upload_file", true);

    $.ajax({
        type: "POST",
        url: link,
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                myXhr.upload.addEventListener('progress', that.progressHandling, false);
            }
            return myXhr;
        },
        success: function (data) {
            //alert (data)
        },
        error: function (error) {
            // handle error
        },
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 60000
    });
};

Upload.prototype.progressHandling = function (event) {
    var percent = 0;
    var position = event.loaded || event.position;
    var total = event.total;
    var progress_bar_id = "#progress-wrp";
    if (event.lengthComputable) {
        percent = Math.ceil(position / total * 100);
    }
    // update progressbars classes so it fits your code
    $(progress_bar_id + " .progress-bar").css("width", +percent + "%");
    $(progress_bar_id + " .status").text(percent + "%");
};


//Change id to your id
$("#IssueReport").on("change", function (e) {
    var file = $(this)[0].files[0];
    var upload = new Upload(file);
    upload.doUpload('aj-listen-upload-files.php');
});

//Change id to your id
$("#issuepicture").on("change", function (e) {
    var file = $(this)[0].files[0];
    var upload = new Upload(file);
    upload.doUpload('aj-listen-upload-picture.php');
});


  </script>

</body>

</html>
