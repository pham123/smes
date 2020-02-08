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
        <div class="container-fluid">
          <div style='text-align:center;'>
            <div class="row">
              <div class="col-12">
                <div class="card" id="proplan">
                  <h5 class="card-header">Production plan</h5>
                  <div class="card-body">
                    <form action="listen-proplan.php" method="post">
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label>Trace Station</label>
                          <select name="TraceStationId" class="form-control" v-model="TraceStationId" @change="loadPlans()" required>
                            <?php 
                            $s = $oDB->sl_all('tracestation',1);
                            echo "<option value=''>trace station</option>";
                            foreach ($s as $key => $value) {
                              echo "<option value='".$value['TraceStationId']."'>".$value['TraceStationName']."</option>";
                            }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label>Date</label>
                          <input type="date" class="form-control" required v-model="ProPlanDate" name="ProPlanDate" @change="loadPlans()">
                        </div>
                      </div>
                      <div class="form-row" v-for="(item, index) in plans">
                        <div class="form-group col-md-1">
                          <label v-if="index==0" for="">#</label>
                          <span class="d-block">{{index+1}}</span>
                        </div>
                        <div class="form-group" style="flex-grow: 1;">
                          <label v-if="index==0">Sản phẩm</label>
                          <v-select 
                          placeholder="chọn sản phẩm"
                          :options="products_data" 
                          :get-option-label="option => option.ProductsName+'/'+option.ProductsNumber"
                          :reduce="product => product.ProductsId" 
                          class="form-control"
                          name="ProductsId[]"
                          required
                          v-model="item.ProductsId">
                            <template #search="{attributes, events}">
                            <input
                              class="vs__search"
                              :required="!item.ProductsId"
                              v-bind="attributes"
                              v-on="events"
                            />
                          </template>
                          </v-select>
                        </div>
                        <input type="hidden" name="ProductsId[]" required :value="item.ProductsId">
                        <?php
                          foreach($shifts as $key => $sh)
                          {
                        ?>
                        <div class="form-group col-md-2">
                          <label v-if="index==0" style="font-size: 14px;"><?php echo $sh['ShiftName'].'('.date('H:i',strtotime($sh['ShiftStart'])).'-'.date('H:i',strtotime($sh['ShiftEnd'])).')'?></label>
                          <input type="number" required name="shift_<?php echo $sh['ShiftId']?>[]" class="form-control" placeholder="SL <?php echo $sh['ShiftInformation'] ?>" v-model="item.<?php echo 'shift_'.$sh['ShiftId']?>">
                        </div>
                        <?php
                          }
                        ?>
                        <div class="form-group col-md-1">
                          <label v-if="index==0" style="font-size: 14px;">Remove</label>
                          <a href="#" @click="removeItem(index)" class="d-block"><i style="margin-top: 5px;" class="text-danger fas fa-times"></i></a>
                        </div>
                      </div>
                      <small class="d-block my-3"><a href="#" class="text-primary" @click="addNewItem()"><i class="fas fa-plus"></i> Add new product</a></small>
                      <div class="">
                        <input class="btn btn-sm btn-primary float-right" type="submit" value="Save" />
                      </div>
                    </form>
                  </div>
                </div>
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
