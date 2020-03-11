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
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 250px;overflow: hidden;font-size: 14px;}.form-group{margin-bottom: 0px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
if (isset($_GET['id'])&&is_numeric($_GET['id'])) {
  $id = safe($_GET['id']);
  $newDB->where('d.DocumentId', $id);
  $newDB->join('section s', 's.SectionId=d.SectionId', 'left');
  $newDB->join('documenttype dt', 'dt.DocumentTypeId=d.DocumentTypeId');
  $thisdoc = $newDB->getOne('document d');
}else{
  header('Location:index.php');
  exit();
}
$newDB->where('DocumentId', $_GET['id']);
$lines = $newDB->get('documentlineapproval');

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

        <div class="container-fluid">

          <h3 class="font-weight-bold"><?php echo $thisdoc['DocumentName']?></h3>
          <div class="row">
          <div class="col-md-8">
          <div class="row">
              <div class="col-md">
                  <span>Bộ phận quản lý: <strong><?php echo $thisdoc['SectionName']?></strong></span>
              </div>    
              <div class="col-md">
                  <span>Loại tài liệu: <strong><?php echo $thisdoc['DocumentTypeName']?></strong></span>
              </div>
          </div>

              <div class="row">
                  <div class="col-md">
                      <span>Miêu tả tài liệu</span>
                      <p class="text-body"><?php echo $thisdoc['DocumentDescription']?></p>
                  </div>
              </div>

              </div>


              <div class="col-md-4">
                <h5>Line approval</h5>
                
                <template v-if="form.lines.length > 0">
                    <div class="form-group mb-1 lines" v-for="(line, index) in form.lines" :key="index">
                        <template v-if="users_data">
                            <v-select 
                            :placeholder="'line ' + (index+1)"
                            :options="users_data" 
                            :get-option-label="u => (u.UsersFullName+'-'+u.SectionName+' '+u.PositionsName)" 
                            :disabled="DocumentSubmit==1"
                            v-model="line.UsersId"
                            :reduce="user => user.UsersId" 
                            class="form-control" />
                        </template>
                        <input type="hidden" name="Lines[]" :value="line.UsersId">
                    </div>
                </template>
                <div class="mb-1" v-if="!DocumentSubmit==1">
                  <a class="text-info" style="font-size: 12px;" href="#" @click="addNewLine()"><i class="fas fa-plus"></i>&nbsp;Add new line</a>
                  &nbsp;&nbsp;
                  <a class="text-danger" style="font-size: 12px;" v-if="form.lines.length > 0" href="#" @click="removeLastLine()"><i class="fas fa-times"></i>&nbsp;Remove last line</a>
                </div>
              </div>
              <div class="col-md-12 mt-3">
                <h5>Email list</h5>
                <p><?php echo $thisdoc['DocumentEmailList']?></p>
              </div>
              </div>

        </div>

  

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
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="../js/axios.min.js"></script>

  <!-- use the latest vue-select release -->
  <script src="../js/vue-select.js"></script>
  <link rel="stylesheet" href="../css/vue-select.css">

  <script src="../js/vform.js"></script>

  <script>
    $(function(){
      const { Form } = window.vform;
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#content',
        data: {
          DocumentId: <?php echo $_GET['id'];?>,
          SectionId: <?php echo $thisdoc['SectionId'].'';?>,
          DocumentTypeId: <?php echo $thisdoc['DocumentTypeId']?>,
          DocumentSubmit: <?php echo $thisdoc['DocumentSubmit']?$thisdoc['DocumentSubmit']:0 ?>,
          EmailList: <?php echo json_encode($thisdoc['DocumentEmailList']?explode(",", $thisdoc['DocumentEmailList']):[]);?>,
          ProPlanId: '',
          TraceStationId: '',
          ProPlanDate:'',
          form: new Form({
            lines: <?php echo json_encode($lines)?>
          }),
          users_data: [],
          shifts_data: []
        },
        methods: {
          addNewLine(){
            this.form.lines.push({user_id: null, id: null});
          },
          removeLastLine(){
            this.form.lines.splice(this.form.lines.length -1, 1)
          }
        },
        created: function(){
          axios.get('/smes/document/editdoc_load_data.php').then(({data}) => {
            this.users_data = data['users'];
          }).catch(() => {
            console.log('error');
          });
        },
        computed: {

        }
      });
    })
  </script>
</body>

</html>
