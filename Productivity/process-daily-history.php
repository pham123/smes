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
$has_fixedcolumn = true;
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -8px;} .vs__selected{white-space: nowrap;max-width: 250px;overflow: hidden;font-size: 14px;}.form-group{margin-bottom: 0px;} table th,table td{border: 1px solid #333;font-size: 14px;}';
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
                <div class="card" id="app">
                  <h5 class="card-header">Process daily history</h5>
                  <p class="my-1">Station:&nbsp;<select v-model="TraceStationId" @change="loadProcessData()">
                    <?php
                    $tracestations = $oDB->sl_all('tracestation'," TraceStationParentId is null");
                    echo "<option value=''>select station</option>";
                    foreach ($tracestations as $key => $value) {
                        echo "<option value='".$value['TraceStationId']."'>".$value['TraceStationName']."</option>";
                    }
                        
                    ?>
                  </select>&nbsp;Date:&nbsp;<input type="date" v-model="ProcessDailyHistoryDate" @change="loadProcessData()"/>&nbsp;<button class="btn btn-info btn-sm" v-if="!isFixed && products_data.length > 0" @click="fixColumns">Fix columns</button></p>
                  <div v-if="this.TraceStationId && this.ProcessDailyHistoryDate && this.products_data.length > 0">
                    <div class="card-body pt-0 pl-0" id="parent" style="overflow: auto;">
                      <table class="my-1 mx-auto d-inline-block table-borderless">
                        <tr style="background-color: none;">
                          <td><strong>Filter:</strong></td>
                          <td style="width: 150px;">
                            <select class="w-100" id="" v-model="TraceStationFilter" @change="loadMachinesProductFilter">
                              <option value="">child process</option>
                              <option v-for="s in childStations" :value="s.TraceStationId">{{s.TraceStationName}}</option>
                            </select>
                          </td>
                          <td style="width: 280px;">
                            <select class="w-100" v-model="ProductFilter">
                              <option value="">product</option>
                              <option v-for="p in products_data_filter" :value="p.ProductsId">{{p.ProductsName}}</option>
                            </select>
                          </td>
                          <td style="width:120px">
                            <select class="w-100" v-model="MachineFilter">
                              <option value="">machine</option>
                              <option v-for="m in machines_data_filter" :value="m.MachinesId">{{m.MachinesName}}</option>
                            </select>
                          </td>
                        </tr>
                      </table>
                      <table class="w-100" id="fixTable">
                        <thead>
                          <tr>
                            <th rowspan="2" style="min-width: 30px;">#</th>
                            <th rowspan="2" style="min-width: 130px;">Process</th>
                            <th rowspan="2" style="min-width: 150px;">Part Name</th>
                            <th rowspan="2" style="min-width: 130px;">Part No</th>
                            <th rowspan="2" style="min-width: 70px;">Mold</th>
                            <th rowspan="2" style="border-right: 2px solid gold;">Machine</th>
                            <th colspan="3" v-for="p in periods_data" style="border-right: 2px solid gold;">{{p.PeriodName}}</th>
                          </tr>
                          <tr class="notincl">
                            <template v-for="p in periods_data">
                              <td class="bg-success text-white">OK</td>
                              <td class="bg-danger">NG</th>
                              <td class="bg-warning" style="border-right: 2px solid gold;">Idle</td>
                            </template>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(p,index) in filteredProcesses">
                            <td>{{index+1}}</td>
                            <td>{{p.TraceStationName}}</td>
                            <td>{{p.ProductsName}}</td>
                            <td>{{p.ProductsNumber}}</td>
                            <td>{{p.ProcessDailyHistoryMold}}</td>
                            <td style="border-right: 2px solid gold;">{{p.MachinesName}}</td>
                            <template v-for="(per,index) in periods_data">
                              <td style="border-right: none;"><input type="number" min="0" style="width: 50px;" :name="'ok_'+p.ProcessDailyHistoryId+'_'+per.PeriodId" @input="test" :value="findOkVal(p,per.PeriodId)"></td>
                              <td style="border-right: none;border-left:none;"><input type="number" min="0" style="width: 50px;" :name="'ng_'+p.ProcessDailyHistoryId+'_'+per.PeriodId" @input="test" :value="findNgVal(p,per.PeriodId)"></td>
                              <td style="border-left: none;border-right: 2px solid gold;"><input type="number" min="0" style="width: 50px;" :name="'idletime_'+p.ProcessDailyHistoryId+'_'+per.PeriodId" @input="test" :value="findIdleVal(p,per.PeriodId)"></td>
                            </template>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <table class="my-2 mx-auto d-inline-block table-borderless">
                      <tr style="background-color: none;">
                        <form @submit.prevent="addProcess()">
                          <td style="width: 200px;">
                            <select class="w-100" id="" v-model="form.TraceStationId" required @change="loadMachinesProduct">
                              <option value="">child process</option>
                              <option v-for="s in childStations" :value="s.TraceStationId">{{s.TraceStationName}}</option>
                            </select>
                          </td>
                          <td style="width: 200px;">
                            <select class="w-100" v-model="form.ProductsId">
                              <option value="">product</option>
                              <option v-for="p in products_data_form" :value="p.ProductsId">{{p.ProductsName}}</option>
                            </select>
                          </td>
                          <td style="width: 130px;" v-if="currentProduct">{{currentProduct?currentProduct.ProductsNumber:''}}</td>
                          <td style="width: 100px;"><input placeholder="mold" type="text" class="w-100" v-model="form.ProcessDailyHistoryMold"></td>
                          <td style="width:200px">
                            <select class="w-100" v-model="form.MachinesId">
                              <option value="">machine</option>
                              <option v-for="m in machines_data" :value="m.MachinesId">{{m.MachinesName}}</option>
                            </select>
                          </td>
                          <td><button @click="addProcess()" class="btn-primary">add</button></td>
                        </form>
                      </tr>
                    </table>
                  </div>
                  <div class="text-danger" v-else-if="this.TraceStationId && this.ProcessDailyHistoryDate">
                    Not have any plan
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
  <script src="../js/tableHeadFixer.js"></script>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="../js/axios.min.js"></script>

<!-- use the latest vue-select release -->
<script src="../js/vue-select.js"></script>
<link rel="stylesheet" href="../css/vue-select.css">

<script src="../js/vform.js"></script>

<script>
  $(function () {
      const { Form } = window.vform;
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#app',
        data: {
          isFixed: false,
          TraceStationFilter: '',
          ProductFilter: '',
          MachineFilter: '',
          form: new Form({
            ProductsId: '',
            ProcessDailyHistoryMold: '',
            TraceStationId: '',
            MachinesId: '',
            ProcessDailyHistoryOk: [],
            ProcessDailyHistoryNg: [],
            ProcessDailyHistoryIdletime: []

          }),
          ProcessDailyHistoryDate:'',
          TraceStationId: '',
          items: [],
          stations_data: [],
          periods_data: [],
          machines_data: [],
          machines_data_filter: [],
          products_data: [],
          products_data_form: [],
          products_data_filter: [],
          processes_data: [],
          processes_uniq_data: []
        },
        methods: {
          test(event){
            let name = event.target.name;
            let value = event.target.value;
            axios.post('updateprocessdata.php',{
              name: name,
              value: value
            }).then((data) => {

            }).catch(()=>{

            });
          },
          fixColumns(){
            if(!this.isFixed)
              $('#fixTable').tableHeadFixer({"head": false, "left": 6});
            this.isFixed=true;
          },
          findOkVal(process, period_id){
            let p = this.processes_data.filter((value,index) => {
              return value['TraceStationId'] == process['TraceStationId'] && value['MachinesId'] == process['MachinesId'] && value['ProductsId'] == process['ProductsId'] && value['PeriodId'] == period_id;
            });
            if(p.length > 0){
              return p[0]['ProcessDailyHistoryOk'];
            }
          },
          findNgVal(process, period_id){
            let p = this.processes_data.filter((value,index) => {
              return value['TraceStationId'] == process['TraceStationId'] && value['MachinesId'] == process['MachinesId'] && value['ProductsId'] == process['ProductsId'] && value['PeriodId'] == period_id;
            });
            if(p.length > 0){
              return p[0]['ProcessDailyHistoryNg'];
            }
          },
          findIdleVal(process, period_id){
            let p = this.processes_data.filter((value,index) => {
              return value['TraceStationId'] == process['TraceStationId'] && value['MachinesId'] == process['MachinesId'] && value['ProductsId'] == process['ProductsId'] && value['PeriodId'] == period_id;
            });
            if(p.length > 0){
              return p[0]['ProcessDailyHistoryIdletime'];
            }
          },
          addProcess(){
            if(!this.form.ProductsId || !this.form.TraceStationId || !this.form.MachinesId || !this.form){
              alert('please select process, product, machine');
              return;
            }
            this.form.post('test1.php?date='+this.ProcessDailyHistoryDate)
            .then(({ data }) => {
              this.loadProcessData();
              this.form.reset();
            });
          },
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
          loadMachinesProduct(){
            if(this.form.TraceStationId){
              axios.get('/smes/productivity/loadmachinesdata.php?tracestationid='+this.form.TraceStationId+'&date='+this.ProcessDailyHistoryDate).then(({data}) => {
                this.machines_data = data['machines'];
                this.products_data_form = data['products'];
              }).catch(() => {
                console.log('error');
              });
            }else{
              // alert('not select station or date');
            }
          },
          loadMachinesProductFilter(){
            if(this.TraceStationId){
              axios.get('/smes/productivity/loadmachinesdata.php?tracestationid='+this.TraceStationFilter+'&date='+this.ProcessDailyHistoryDate).then(({data}) => {
                this.machines_data_filter = data['machines'];
                this.products_data_filter = data['products'];
              }).catch(() => {
                console.log('error');
              });
            }else{
              // alert('not select station or date');
            }
          },
          loadProcessData(){
            if(this.TraceStationId && this.ProcessDailyHistoryDate){
              axios.get('/smes/productivity/loadprocessdata.php?tracestationid='+this.TraceStationId+'&date='+this.ProcessDailyHistoryDate).then(({data}) => {
                // this.machines_data = data['machines'];
                this.products_data = data['products'];
                this.processes_data = data['processes'];
                this.processes_uniq_data = data['processes_uniq'];
                this.isFixed = false;
              }).catch(() => {
                console.log('error');
              });
            }else{
              // alert('not select station or date');
            }
          }
        },
        created: function(){
          axios.get('/smes/productivity/loadprocessdailyhistorydata.php').then(({data}) => {
            this.stations_data = data['tracestations'];
            this.periods_data = data['periods'];
          }).catch(() => {
            console.log('error');
          });
        },
        computed: {
          validState: function () {
            return true;

          },
          childStations: function(){
            return this.stations_data.filter((value,index) => {
              return value.TraceStationParentId == this.TraceStationId || value.TraceStationId == this.TraceStationId;
            });
          },
          currentProduct: function(){
            return this.products_data.filter((value,index) => {
              return value['ProductsId'] == this.form.ProductsId
            })[0];
          },
          filteredProcesses: function(){
            let result = this.processes_uniq_data;
            if(this.TraceStationFilter != ''){
              result = result.filter((value,index) => {
                return value['TraceStationId'] == this.TraceStationFilter;
              });
            }
            if(this.ProductFilter != ''){
              result = result.filter((value,index) => {
                return value['ProductsId'] == this.ProductFilter;
              });
            }
            if(this.MachineFilter != ''){
              result = result.filter((value,index) => {
                return value['MachinesId'] == this.MachineFilter;
              });
            }
            return result;
          }
        }
    });
    })
  </script>

</body>

</html>
