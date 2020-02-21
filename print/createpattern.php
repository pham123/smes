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
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -8px;} .vs__selected{white-space: nowrap;overflow: hidden;font-size: 14px;} .form-group{margin-bottom: 0px;}';
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
                  <h5 class="card-header">Set label pattern &amp; machine</h5>
                  <div class="card-body">
                    <form action="listen-create-pattern.php" class="" method="post">
                      <div class="form-row">
                    <label for="TraceStationId">Trace station:&nbsp;</label>
                    <select name="TraceStationId" class="form-control" v-model="TraceStationId" @change="loadPattern()" required>
                        <?php 
                        $s = $oDB->sl_all('tracestation',1);
                        echo "<option value=''>trace station</option>";
                        foreach ($s as $key => $value) {
                          echo "<option value='".$value['TraceStationId']."'>".$value['TraceStationName']."</option>";
                        }
                        ?>
                      </select>
                      </div>
                      <div class="form-row" v-for="(item, index) in labelpatterns">
                        <input type="hidden" name="LabelPatternId[]" v-model="item.LabelPatternId" />
                        <div class="form-group col-md-1">
                          <label v-if="index==0" for="" style="font-size: 14px;font-weight: bold;">LabelPatternId</label>
                          <span class="d-block">{{item.LabelPatternId}}</span>
                        </div>
                        <div class="form-group" style="flex-grow: 1; margin-top: 0px;">
                          <label v-if="index==0" style="font-size: 14px; font-weight: bold;">Sản phẩm</label>
                          <v-select 
                          placeholder="chọn sản phẩm"
                          :options="products_data" 
                          :get-option-label="option => option.ProductsName+'/'+option.ProductsNumber"
                          :reduce="product => product.ProductsId" 
                          class="form-control"
                          name="ProductsId[]"
                          :disabled=!validState
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
                        <div class="form-group col-md-3">
                          <label v-if="index==0" style="font-size: 14px;font-weight: bold;">Label</label>
                          <input type="text" class="form-control" v-model="item.LabelPatternValue" name="LabelPatternValue[]" required>
                        </div>
                        <div class="form-group col-md-2">
                          <label v-if="index==0" style="font-size: 14px;font-weight: bold">Packing standard</label>
                          <input type="number" class="form-control" v-model="item.LabelPatternPackingStandard" name="LabelPatternPackingStandard[]">
                        </div>
                        <div class="form-group col-md-1">
                          <label v-if="index==0" style="font-size: 14px;font-weight: bold;">Remove</label>
                          <a href="#" @click="removeItem(index)" class="d-block"><i style="margin-top: 5px;" class="text-danger fas fa-times" v-show="validState"></i></a>
                        </div>
                      </div>
                      <small class="d-block my-3"><a v-show="validState" href="#" class="text-primary" @click="addNewItem()"><i class="fas fa-plus"></i> Add new pattern</a></small>
                      <div class="form-row" v-for="(mc, index) in machines">
                        <input type="hidden" name="AssignMachinesId[]" v-model="mc.AssignMachinesId" />
                        <div class="form-group col-md-2">
                          <label v-if="index==0" for="" style="font-size: 14px;font-weight: bold;">AssignMachinesId</label>
                          <span class="d-block">{{mc.AssignMachinesId}}</span>
                        </div>
                        <div class="form-group" style="flex-grow: 1; margin-top: 0px;">
                          <label v-if="index==0" style="font-size: 14px; font-weight: bold;">Machine</label>
                          <v-select 
                          placeholder="machine"
                          :options="machines_data" 
                          :get-option-label="option => option.MachinesName"
                          :reduce="machine => machine.MachinesId" 
                          class="form-control"
                          name="MachinesId[]"
                          :disabled=!validState
                          required
                          v-model="mc.MachinesId">
                            <template #search="{attributes, events}">
                            <input
                              class="vs__search"
                              :required="!mc.MachinesId"
                              v-bind="attributes"
                              v-on="events"
                            />
                          </template>
                          </v-select>
                        </div>
                        <input type="hidden" name="MachinesId[]" required :value="mc.MachinesId">
                        <div class="form-group col-md-4">
                          <label v-if="index==0" style="font-size: 14px;font-weight: bold;">Description</label>
                          <input type="text" class="form-control" v-model="mc.AssignMachinesDescription" name="AssignMachinesDescription[]">
                        </div>
                        <div class="form-group col-md-1">
                          <label v-if="index==0" style="font-size: 14px;font-weight: bold;">Remove</label>
                          <a href="#" @click="removeMachine(index)" class="d-block"><i style="margin-top: 5px;" class="text-danger fas fa-times" v-show="validState"></i></a>
                        </div>

                      </div>
                      <small class="d-block my-3"><a v-show="validState" href="#" class="text-primary" @click="addNewMachine()"><i class="fas fa-plus"></i> Add new machine</a></small>
                      <div class="">
                        <input v-show="validState" class="btn btn-sm btn-primary float-right" type="submit" value="Save" />
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
        el: '#app',
        data: {
          TraceStationId: '',
          labelpatterns:[],
          machines: [],
          products_data: [],
          machines_data: []
        },
        methods: {
          addNewItem(){
            let pattern = {LabelPatternId: 'new',ProductsId: null,LabelPatternValue: '',LabelPatternPackingStandard:0};
            this.labelpatterns.push(pattern);
          },
          addNewMachine(){
            let machine = {AssignMachinesId: 'new',MachinesId: null,AssignMachinesDescription: ''};
            this.machines.push(machine);
          },
          removeItem(index){
            if(this.labelpatterns.length == 0)
            {
              return;
            }
            this.labelpatterns.splice(index,1);
          },
          removeMachine(index){
            if(this.machines.length == 0)
            {
              return;
            }
            this.machines.splice(index,1);
          },
          loadPattern(){
            if(this.TraceStationId){
              axios.get('/smes/print/loadpatternajax.php?tracestationid='+this.TraceStationId).then(({data}) => {
                this.labelpatterns = data['patterns'];
                this.machines = data['assigns'];
              }).catch(() => {
                console.log('error');
              });
            }else{
              console.log('');
            }
          }
        },
        created: function(){
          axios.get('/smes/print/loadpatternajax.php').then(({data}) => {
            this.products_data = data['products'];
            this.machines_data = data['machines'];
          }).catch(() => {
            console.log('error');
          });
        },
        computed: {
          validState: function () {
            return this.TraceStationId != ''
          }
        }
    });
    })
  </script>

</body>

</html>
