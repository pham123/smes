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
          <h4 class="text-center"><strong>XUẤT HÀNG</strong></h4>
          <form action="listen-stockout.php" method="post">
            <input type="hidden" v-model="StockOutputsId" name="StockOutputsId">
            <div class="form-group row">
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>FROM(TỪ):</strong></label>
              <div class="col-sm-3">
                <select class="form-control" required v-model="FromId" name="FromId">
                  <option value="">select</option>
                  <?php 
                  foreach ($scobjs as $key => $value) {
                    echo "<option value='".$value['SupplyChainObjectId']."'>".$value['SupplyChainObjectName']."</option>";
                  }
                  ?>            
                </select>
              </div>
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>KHO:</strong></label>
              <div class="col-sm-3">
                <select class="form-control" name="StockOutputsType" v-model="StockOutputsType">
                    <option value="">select</option>
                    <option value="KHO THÀNH PHẨM">WIP</option>
                    <option value="Finish Good">Finish Good</option>
                </select>
              </div>
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>BKS:</strong></label>
              <div class="col-sm-3">
                <input type="text" class="form-control" v-model="StockOutputsBks" name="StockOutputsBks">
              </div>
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>TO(ĐẾN):</strong></label>
              <div class="col-sm-3">
                <select class="form-control" required v-model="ToId" name="ToId">
                  <option value="">select</option>
                  <?php 
                  foreach ($scobjs as $key => $value) {
                    echo "<option value='".$value['SupplyChainObjectId']."'>".$value['SupplyChainObjectName']."</option>";
                  }
                  ?>            
                </select>
              </div>
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>NO:</strong></label>
              <div class="col-sm-3">
                <input readonly type="text" class="form-control" v-model="StockOutputsNo" name="StockOutputsNo">
              </div>
              <label style="font-size: 14px;" class="col-sm-2 col-form-label"><strong>THỜI GIAN(TIME):</strong></label>
              <div class="col-sm-1">
              </div>
              <label style="font-size: 14px;" class="col-sm-2 col-form-label"><strong>DELIVERY DATE<br>(NGÀY GIAO HÀNG)</strong></label>
              <div class="col-sm-2">
                <input type="date" class="form-control" v-model="StockOutputsDate" name="StockOutputsDate" required>
              </div>
              <label style="font-size: 14px;" class="col-sm-1 col-form-label"><strong>MODEL:</strong></label>
              <div class="col-sm-3">
                <select class="form-control" required v-model="ModelsId" name="ModelsId">
                  <option value="">select</option>
                  <?php 
                  foreach ($models as $key => $value) {
                    echo "<option value='".$value['ModelsId']."'>".$value['ModelsName']."</option>";
                  }
                  ?>            
                </select>
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
                  <tr v-for="(item,index) in stockoutputitems">
                    <td>{{index+1}}</td>
                    <td>
                      <v-select 
                        placeholder="chọn sản phẩm"
                        :options="products_data" 
                        :get-option-label="option => option.ProductsName"
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
                    <td><input type="text" v-model="item.StockOutputItemsWo" name="StockOutputItemsWo[]"></td>
                    <td><input type="number" v-model="item.StockOutputItemsCartQty" style="width:60px;" name="StockOutputItemsCartQty[]"></td>
                    <td>{{productSelected(item).ProductsUnit}}</td>
                    <td><input type="number" v-model="item.StockOutputItemsQty" style="width:60px;" name="StockOutputItemsQty[]"></td>
                    <td><input type="text" v-model="item.StockOutputItemsRemark" name="StockOutputItemsRemark[]"></td>
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
                <a :href="'print-stockout.php?id='+StockOutputsId" target="_blank" class="btn btn-secondary mt-auto"><i class="fas fa-print"></i></a>
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
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#content',
        data: {
          StockOutputsId: null,
          UsersId: '',
          FromId: '',
          ToId: '',
          ModelsId: '',
          StockOutputsDate: null,
          StockOutputsNo: '',
          StockOutputsType: '',
          StockOutputsBks: '',
          StockOutputsStatus: 0,
          stockoutputitems:[{
            ProductsId:'',
            StockOutputItemsWo: '',
            StockOutputItemsCartQty:'',
            StockOutputItemsQty:'',
            StockOutputItemsRemark:'',
          }],
          products_data: []
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
            this.stockoutputitems.push({
              ProductsId:'',
              StockOutputItemsWo: '',
              StockOutputItemsCartQty: '',
              StockOutputItemsQty:'',
              StockOutputItemsRemark:'',
            });
          },
          removeLastItem(){
            if(this.stockoutputitems.length == 0)
            {
              return;
            }
            this.stockoutputitems.splice(-1,1);
          },
          removeItem(index){
            if(this.stockoutputitems.length == 0)
            {
              return;
            }
            this.stockoutputitems.splice(index,1);
          },
          loadPlans(){
            if(this.TraceStationId && this.ProPlanDate){
              axios.get('/smes/purchase/loadpurchasedata.php').then(({data}) => {
                this.products = data['products'];
              }).catch(() => {
                console.log('error');
              });
            }else{
              console.log('station or date not select');
            }
          }
        },
        created: function(){
          axios.get('/smes/warehouse/loadwarehousedata.php').then(({data}) => {
            this.products_data = data['products_data'];
            this.StockOutputsId = data['StockOutputsId'];
            this.FromId = data['FromId']==0?'':data['FromId'];
            this.ToId = data['ToId']==0?'':data['ToId'];
            this.ModelsId = data['ModelsId']==0?'':data['ModelsId'];
            this.StockOutputsType = data['StockOutputsType']+'';
            this.StockOutputsDate = data['StockOutputsDate'];
            this.StockOutputsNo = data['StockOutputsNo'];
            this.StockOutputsBks = data['StockOutputsBks'];
            this.stockoutputitems = data['stockoutputitems'];
          }).catch(() => {
            console.log('error');
          });
        }
    });
    })
  </script>

</body>

</html>
