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
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 250px;overflow: hidden;font-size: 14px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$shifts = $newDB->get('shift');
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
if (!is_dir(__DIR__."\logs")) {
  mkdir(__DIR__."\logs", 0700);
}
w_logs(__DIR__."\logs\\login\\", $_SESSION[_site_]['userid'].'_'.$_SESSION[_site_]['username'].' access');
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

        <!-- Begin Page Content -->
  

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

  <script>
    $(function () {
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#proplan',
        data: {
          ProPlanId: '',
          TraceStationId: '',
          ProPlanDate:'',
          plans:[],
          products_data: [],
          shifts_data: []
        },
        methods: {
          addNewItem(){
            if(this.TraceStationId=='' || this.ProPlanDate==''){
              alert('Please select station and date');
              return;
            }
            let plan = {ProductsId: null};
            this.shifts_data.forEach((value,key) => {
              let plan_shift_key = 'shift_'+value['ShiftId'];
              plan[plan_shift_key] = 0;
            })
            this.plans.push(plan);
          },
          removeLastItem(){
            if(this.plans.length == 0)
            {
              return;
            }
            this.plans.splice(-1,1);
          },
          removeItem(index){
            if(this.plans.length == 0)
            {
              return;
            }
            this.plans.splice(index,1);
          },
          loadPlans(){
            if(this.TraceStationId && this.ProPlanDate){
              axios.get('/smes/productivity/loadplanajax.php?tracestationid='+this.TraceStationId+'&date='+this.ProPlanDate).then(({data}) => {
                console.log(data);
                this.plans = data['plans'];
              }).catch(() => {
                console.log('error');
              });
            }else{
              console.log('station or date not select');
            }
          }
        },
        created: function(){
          axios.get('/smes/productivity/proplanajax.php').then(({data}) => {
            this.products_data = data['products_data'];
            this.shifts_data = data['shifts_data'];
          }).catch(() => {
            console.log('error');
          });
        }
    });
    })
  </script>

</body>

</html>
