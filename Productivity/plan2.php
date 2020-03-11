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
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 500px;overflow: hidden;font-size: 12px;}.form-group{margin-bottom: 0px;} table th,table td{border: 1px solid #333;font-size: 14px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$shifts = $newDB->get('shift');
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}

for($i = 1; $i <=  date('t'); $i++)
{
   $dates[] = str_pad($i, 2, '0', STR_PAD_LEFT);
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
                  <h5 class="card-header">Plans in <?php echo date('Y-m');?></h5>
                  <p class="my-1">Station:&nbsp;<select v-model="TraceStationId" @change="loadPlanData()">
                    <?php
                    $tracestations = $oDB->sl_all('tracestation',"1");
                    echo "<option value=''>select station</option>";
                    foreach ($tracestations as $key => $value) {
                        echo "<option value='".$value['TraceStationId']."'>".$value['TraceStationName']."</option>";
                    }
                        
                    ?>
                  </select>&nbsp;<button class="btn btn-info btn-sm" v-if="!isFixed && plans_data.length > 0" @click="fixColumns">Fix columns</button></p>
                  <div v-if="this.TraceStationId">
                    <div class="card-body pt-0 pl-0" id="parent" style="overflow: auto;" v-if="this.plans_uniq_data.length>0">
                      <table class="my-1 mx-auto d-inline-block table-borderless">
                        <tr style="background-color: none;">
                          <td><strong>Filter:</strong></td>
                          <td style="width: 280px;">
                            <v-select 
                            placeholder="chọn sản phẩm"
                            :options="products_data" 
                            :get-option-label="option => option.ProductsNumber+'-'+option.ProductsName"
                            :reduce="product => product.ProductsId" 
                            class="form-control"
                            :disabled=!validState
                            required
                            v-model="ProductFilter">
                              <template #search="{attributes, events}">
                              <input
                                class="vs__search"
                                :required="!ProductFilter"
                                v-bind="attributes"
                                v-on="events"
                              />
                            </template>
                            </v-select>
                          </td>
                        </tr>
                      </table>
                      <table class="w-100" id="fixTable">
                        <thead>
                          <tr>
                            <th rowspan="2" style="min-width: 30px;">#</th>
                            <th rowspan="2" style="min-width: 150px;">Part Name</th>
                            <th rowspan="2" style="min-width: 130px; border-right: 2px solid gold;">Part No</th>
                            <th :colspan="shifts_data.length" v-for="d in daysInMonth" style="border-right: 2px solid gold;">{{d}}</th>
                          </tr>
                          <tr class="notincl">
                            <template v-for="d in daysInMonth">
                              <td v-for="(sh,j) in shifts_data" :style="(j == (shifts_data.length-1))?'border-right: 2px solid gold;':'border-right:none;'">
                                {{sh.ShiftName}}
                              </td>
                            </template>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(p,index) in filteredPlans">
                            <td>{{index+1}}</td>
                            <td>{{p.ProductsName}}</td>
                            <td style="border-right: 2px solid gold;;">{{p.ProductsNumber}}</td>
                            <template v-for="(d,i) in daysInMonth">
                              <td v-for="(sh,j) in shifts_data" :style="(j == (shifts_data.length-1))?'border-right: 2px solid gold;':'border-right:none;'">
                                <span v-if="checkValidState(d)">
                                  <input type="number" min="0" style="width: 60px;" :name="'date_'+monthYear+'-'+d+'_'+p.ProPlanId+'_'+sh.ShiftId" @input="test" :value="findQty(p,d,sh.ShiftId)">
                                </span>
                                <span v-else style="width: 60px;display:inline-block;">
                                  {{findQty(p,d,sh.ShiftId)}}
                                </span>
                              </td>

                            </template>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="text-danger text-center" v-else>
                      Not have any data
                    </div>
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
  Number.prototype.pad = function(size) {
    var s = String(this);
    while (s.length < (size || 2)) {s = "0" + s;}
    return s;
  }
  $(function () {
      const { Form } = window.vform;
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#app',
        data: {
          isFixed: false,
          ProductFilter: '',
          form: new Form({
            ProductsId: ''
          }),
          monthYear: '<?php echo "".date("Y")."-".date("m"); ?>',
          daysInMonth: <?php echo json_encode($dates) ?>,
          TraceStationId: '',
          items: [],
          stations_data: [],
          shifts_data: [],
          products_data: [],
          plans_data: [],
          plans_uniq_data: []
        },
        methods: {
          test(event){
            let name = event.target.name;
            let value = event.target.value;
            axios.post('updateplandata.php',{
              name: name,
              value: value
            }).then((data) => {

            }).catch(()=>{

            });
          },
          checkValidState(day){
            let date = this.monthYear+'-'+day;
            let now = new Date();
            let todayStr = now.getFullYear().toString() + '-' + ((now.getMonth() + 1)>=10?(now.getMonth()+1) : '0'+(now.getMonth()+1)).toString() + '-' + (now.getDate() >=10 ? now.getDate().toString() : '0'+ (now.getDate().toString())).toString();
            let hm = (now.getHours()).pad()+':'+(now.getMinutes()).pad();
            if(hm > '16:00'){
              if(todayStr >= date){
                return false
              }else{
                return true;
              }
            }else{
              if(todayStr > date){
                return false
              }else{
                return true;
              }
            }
          },
          fixColumns(){
            if(!this.isFixed)
              $('#fixTable').tableHeadFixer({"head": false, "left": 3});
            this.isFixed=true;
          },
          findQty(plan, day, shiftId){
            let date = this.monthYear+'-'+day;
            let p = this.plans_data.filter((value,index) => {
              return value['TraceStationId'] == plan['TraceStationId'] && value['ProductsId'] == plan['ProductsId'] && value['ProPlanDate'] == date && value['ShiftId'] == shiftId;
            });
            if(p.length > 0){
              return p[0]['ProPlanQuantity'];
            }
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
          loadPlanData(){
            if(this.TraceStationId){
              axios.get('/smes/productivity/load_plans_when_station_change_2.php?tracestationid='+this.TraceStationId).then(({data}) => {
                this.plans_data = data['plans'];
                this.plans_uniq_data = data['plans_uniq'];
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
          axios.get('/smes/productivity/loadplandata2.php').then(({data}) => {
            this.stations_data = data['tracestations'];
            this.shifts_data = data['shifts'];
            this.products_data = data['products'];
          }).catch(() => {
            console.log('error');
          });
        },
        computed: {
          validState: function () {
            return true;

          },
          filteredPlans: function(){
            let result = this.plans_uniq_data;
            if(this.ProductFilter != ''){
              result = result.filter((value,index) => {
                return value['ProductsId'] == this.ProductFilter;
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
