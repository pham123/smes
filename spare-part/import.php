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
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$newDB->where('ProductsOption', 4);
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
          <div class="row">
            <div class="col-12">
              <div class="card" id="import_spare_part">
                <h5 class="card-header">Nhập hàng</h5>
                <div class="card-body">
                  <form action="listen-import.php" method="post">
                    <input type="hidden" v-model="Import.ImportsId" name="ImportsId" />
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label>Số PO <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" name="ImportsPO" v-model="Import.ImportsPO" required>
                      </div>
                      <div class="form-group col-md-4">
                        <label>Doc No <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" name="ImportsDocNo" v-model="Import.ImportsDocNo" required>
                      </div>
                      <div class="form-group col-md-4">
                        <label>Nhà cung cấp <sup class="text-danger">*</sup></label>
                        <select name="SuppliersId" class="form-control" required v-model="Import.SuppliersId">
                          <?php 
                          $spls = $oDB->sl_all('supplychainobject','SupplyChainTypeId = 3');
                          echo "<option value=''>select supplier</option>";
                          foreach ($spls as $key => $value) {
                            echo "<option value='".$value['SupplyChainObjectId']."'>".$value['SupplyChainObjectName']."</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Ngày nhập <sup class="text-danger">*</sup></label>
                        <input type="date" class="form-control" required name="ImportsDate" v-model="Import.ImportsDate">
                      </div>
                      <div class="form-group col-md-6">
                        <label>Ghi chú</label>
                        <input type="text" class="form-control" name="ImportsNote" v-model="Import.ImportsNote">
                      </div>
                    </div>
                    <div class="form-row" v-for="(item, index) in items">
                      <div class="form-group col-6">
                        <label v-if="index==0">Mã hàng <sup class="text-danger">*</sup></label>
                        <v-select 
                        placeholder="chọn sản phẩm"
                        :options="products_data" 
                        :get-option-label="option => option.ProductsName+'/'+option.ProductsNumber+'(Đơn vi:'+option.ProductsUnit+')'"
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
                      <div class="form-group col-md-2">
                        <label v-if="index==0">Số lượng <sup class="text-danger">*</sup></label>
                        <input type="number" required name="ProductsQty[]" :onkeyup="calculateMoney(item)" v-model="item.ProductsQty" class="form-control">
                      </div>
                      <div class="form-group col-md-2">
                        <label v-if="index==0">Đơn giá</label>
                        <money v-model="item.ProductsUnitPrice" v-bind="money" name="ProductsUnitPrice[]" @keyup.native="calculateMoney(item)" class="form-control" required></money>
                      </div>
                      <div class="form-group col-md-2">
                        <label v-if="index==0">Thành tiền</label>
                        <p><strong>{{item.ProducstMoney.format()}}</p>
                      </div>
                    </div>
                    <small class="d-block mb-3 mt-5"><a href="#" class="text-primary" @click="addNewItem()">Add more product</a> | <a href="#" class="text-danger" @click="removeLastItem()">Remove last product</a></small>
                    <div class="">
                      <input class="btn btn-sm btn-success float-right ml-2" type="submit" name="importBtn" value="Import" />
                      <input class="btn btn-sm btn-primary float-right" type="submit" name="saveBtn" value="Save" />
                    </div>
                  </form>
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

  <script src="../js/vuejs.min.js"></script>
  <script src="../js/axios.min.js"></script>
  
  <!-- use the latest vue-select release -->
  <script src="../js/vue-select.js"></script>
  <link rel="stylesheet" href="../css/vue-select.css">
  <script type="module" src="../js/v-money.js">
    import money from '../js/v-money.js';
    Vue.use(money, {precision: 4});
  </script>

  <script>
    Number.prototype.format = function(n, x) {
      var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
      return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
  };
    $(function () {
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#import_spare_part',
        data: {
          items:[{ProductsId:'',ProductsQty: '', ProductsUnitPrice:'', ProducstMoney:''}],
          Import:{
            ImportsId: '',
            ImportsPO:'',
            ImportsDocNo:'',
            ImportsDate:'',
            SuppliersId: 0,
            ImportsNote: ''
          },
          products_data: [],
          money: {
            decimal: '.',
            thousands: ',',
            prefix: '',
            suffix: '',
            precision: 0,
            masked: false /* doesn't work with directive */
          }
        },
        methods: {
          addNewItem(){
            this.items.push({ProductsId: null, ProductsQty: '', ProductUnitPrice: '', ProducstMoney:''})
          },
          removeLastItem(){
            if(this.items.length == 1)
            {
              return;
            }
            this.items.splice(-1,1);
          },
          calculateMoney(item){
            item.ProducstMoney = parseFloat(item.ProductsQty) * parseFloat(item.ProductsUnitPrice);
          }
        },
        created: function(){
          axios.get('/smes/spare-part/importajax.php').then(({data}) => {
            this.products_data = data['products_data'];
            this.Import.ImportsId = data['ImportsId'];
            this.Import.ImportsPO = data['ImportsPO'];
            this.Import.ImportsDocNo = data['ImportsDocNo'];
            this.Import.SuppliersId = data['SuppliersId'];
            this.Import.ImportsDate = data['ImportsDate'];
            this.Import.ImportsNote = data['ImportsNote'];
            if(data['inputs'].length>0){
              this.items = data['inputs'];
            }else{
              this.items = [{ProductsId:'',ProductsQty: '', ProductsUnitPrice:'', ProducstMoney:''}];
            }
          }).catch((error) => {
            console.log(error);
          });
        }
      })
    });
  </script>

</body>

</html>
