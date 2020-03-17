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
$page_css='th,td{font-weight: normal;font-size: 13px;text-align: center;vertical-align: middle !important;}.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 200px;overflow: hidden;font-size: 13px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$shifts = $newDB->get('shift');
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
$scobjs = $newDB->get('supplychainobject');
$models = $newDB->get('models');
$tracestations = $newDB->get('tracestation');

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
        <div class="mx-1">
          <h4 class="text-center"><strong>NHẬP HÀNG</strong></h4>
          <form action="listen-stockin.php" method="post">
            <input type="hidden" v-model="StockInputsId" name="StockInputsId">
            <div class="form-group row">
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>FROM(TỪ):</strong></label>
              <div class="col-sm-3">
                <v-select 
                  placeholder="select"
                  :options="suppliers_data" 
                  :get-option-label="option => option.SupplyChainObjectName"
                  :reduce="spl => spl.SupplyChainObjectId" 
                  class="form-control"
                  v-model="FromId"
                  required>
                    <template #search="{attributes, events}">
                      <input
                        class="vs__search"
                        :required="!FromId"
                        v-bind="attributes"
                        v-on="events"
                      />
                    </template>
                </v-select>
                <input type="hidden" name="FromId" required :value="FromId">
                
              </div>
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>KHO:</strong></label>
              <div class="col-sm-3">
                <select class="form-control" name="StockInputsType" v-model="StockInputsType">
                    <option value="">select</option>
                    <option value="WIP">WIP</option>
                    <option value="Finish Good">Finish Good</option>
                </select>
              </div>
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>BKS:</strong></label>
              <div class="col-sm-3">
              </div>
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>TO(ĐẾN):</strong></label>
              <div class="col-sm-3">
                <v-select 
                  placeholder="select"
                  :options="suppliers_data" 
                  :get-option-label="option => option.SupplyChainObjectName"
                  :reduce="spl => spl.SupplyChainObjectId" 
                  class="form-control"
                  v-model="ToId"
                  required>
                    <template #search="{attributes, events}">
                      <input
                        class="vs__search"
                        :required="!ToId"
                        v-bind="attributes"
                        v-on="events"
                      />
                    </template>
                </v-select>
                <input type="hidden" name="ToId" required :value="ToId">
              </div>
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>NO:</strong></label>
              <div class="col-sm-3">
                <input readonly type="text" class="form-control" v-model="StockInputsNo" name="StockInputsNo">
              </div>
              <label style="font-size: 14px;" class="col-sm-2 col-form-label"><strong>THỜI GIAN(TIME):</strong></label>
              <div class="col-sm-1">
              </div>
              <label style="font-size: 14px;" class="col-sm-2 col-form-label"><strong>DELIVERY DATE<br>(NGÀY GIAO HÀNG)</strong></label>
              <div class="col-sm-2">
                <input type="date" class="form-control" v-model="StockInputsDate" name="StockInputsDate" required>
              </div>
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>MODEL:</strong></label>
              <div class="col-sm-3">
                <v-select 
                  placeholder="select"
                  :options="models_data" 
                  :get-option-label="option => option.ModelsName"
                  :reduce="m => m.ModelsId" 
                  class="form-control"
                  v-model="ModelsId"
                  required>
                    <template #search="{attributes, events}">
                      <input
                        class="vs__search"
                        :required="!ModelsId"
                        v-bind="attributes"
                        v-on="events"
                      />
                    </template>
                </v-select>
                <input type="hidden" name="ModelsId" required :value="ModelsId">
              </div>
              
            
            </div>
            <div class="w-100" style="overflow: auto;">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th><strong>NO</strong><br><em>STT</em></th>
                    <th style="min-width: 350px;"><strong>Part name</strong></th>
                    <th style="min-width: 150px;"><strong>Part No</strong></th>
                    <th><strong>W/o</strong></th>
                    <th><strong>Cart Q'ty</strong></th>
                    <th><strong>Unit</strong></th>
                    <th><strong>Q'ty</strong></th>
                    <th><strong>Remark</strong></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item,index) in stockinputitems">
                    <td>{{index+1}}</td>
                    <td>
                      <v-select 
                        placeholder="chọn sản phẩm"
                        :options="products_data" 
                        :get-option-label="option => option.ProductsNumber + '-' +option.ProductsName"
                        :reduce="product => product.ProductsId" 
                        class="form-control"
                        @input="productSelected(item)"
                        v-model="item.ProductsId"
                        required>
                          <template #search="{attributes, events}">
                            <input
                              class="vs__search"
                              :required="!item.ProductsId"
                              v-bind="attributes"
                              v-on="events"
                            />
                          </template>
                      </v-select>
                      <input type="hidden" name="ProductsId[]" required :value="item.ProductsId">
                    </td>
                    <td>{{productSelected(item).ProductsNumber}}</td>
                    <td><input style="height: 33px; font-size: 16px;" type="text" v-model="item.StockInputItemsWo" name="StockInputItemsWo[]"></td>
                    <td><input style="height: 33px; font-size: 16px; width: 60px" type="number" v-model="item.StockInputItemsCartQty" name="StockInputItemsCartQty[]"></td>
                    <td>{{productSelected(item).ProductsUnit}}</td>
                    <td><input style="height: 33px; font-size: 16px;width: 60px;" type="number" v-model="item.StockInputItemsQty" name="StockInputItemsQty[]"></td>
                    <td><input style="height:33px; font-size: 16px;" type="text" v-model="item.StockInputItemsRemark" name="StockInputItemsRemark[]"></td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th colspan="2"><strong>SUM</strong></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <p class="my-1"><a href="#" class="text-primary" @click.stop.prevent="addNewItem()"><small><strong>Add new product</strong></small></a>|<a href="#" class="text-danger" @click.stop.prevent="removeLastItem()"><small><strong>Remove last product</strong></small></a></p>
            <div class="row pb-5 pt-3">
              <div class="col-md-6 d-flex">
                <button name="saveBtn" class="btn btn-primary mr-1 mt-auto">Save</button>
                <button name="submitBtn" class="btn btn-success mr-1 mt-auto">Submit</button>
                <a :href="'print-stockin.php?id='+StockInputsId" target="_blank" class="btn btn-secondary mt-auto"><i class="fas fa-print"></i></a>
              </div>
            </form>
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

  <script>
    $(function () {
      $('.selectpicker').selectpicker();
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#content',
        data: {
          StockInputsId: null,
          UsersId: '',
          FromId: '',
          ToId: '',
          ModelsId: '',
          StockInputsDate: null,
          StockInputsNo: '',
          StockInputsType: '',
          StockInputsStatus: 0,
          stockinputitems:[{
            ProductsId:'',
            StockInputItemsWo: '',
            StockInputItemsCartQty:'',
            StockInputItemsQty:'',
            StockInputItemsRemark:'',
          }],
          products_data: [],
          suppliers_data: <?php echo json_encode($scobjs);?>,
          models_data: <?php echo json_encode($models); ?>,
        },
        methods: {
          productSelected(item){
            let product = this.products_data.filter((p) => {return p.ProductsId == item.ProductsId});
            if(product.length == 0){
              return {
                ProductsId: '',
                ProductsName:''
              };
            }
            return product[0];
          },
          addNewItem(){
            this.stockinputitems.push({
              ProductsId:'',
              StockInputItemsWo: '',
              StockInputItemsCartQty: '',
              StockInputItemsQty:'',
              StockInputItemsRemark:'',
            });
          },
          removeLastItem(){
            if(this.stockinputitems.length == 0)
            {
              return;
            }
            this.stockinputitems.splice(-1,1);
          },
          removeItem(index){
            if(this.stockinputitems.length == 0)
            {
              return;
            }
            this.stockinputitems.splice(index,1);
          }
        },
        created: function(){
          axios.get('/smes/warehouse/loadstockinputdata.php').then(({data}) => {
            this.products_data = data['products_data'];
            this.StockInputsId = data['StockInputsId'];
            this.FromId = data['FromId']==0?'':data['FromId'];
            this.ToId = data['ToId']==0?'':data['ToId'];
            this.ModelsId = data['ModelsId']==0?'':data['ModelsId'];
            this.StockInputsType = data['StockInputsType']+'';
            this.StockInputsDate = data['StockInputsDate'];
            this.StockInputsNo = data['StockInputsNo'];
            this.stockinputitems = data['stockinputitems'];
          }).catch(() => {
            console.log('error');
          });
        }
    });
    })
  </script>

</body>

</html>
