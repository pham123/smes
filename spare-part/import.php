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
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Số PO <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" required name="ImportsPO">
                      </div>
                      <div class="form-group col-md-6">
                        <label>Nhà cung cấp <sup class="text-danger">*</sup></label>
                        <select name="SuppliersId" class="form-control">
                          <?php 
                          $spls = $oDB->sl_all('supplychainobject',1);
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
                        <input type="date" class="form-control" required name="ImportsDate">
                      </div>
                      <div class="form-group col-md-6">
                        <label>Ghi chú</label>
                        <input type="text" class="form-control" name="ImportsNote">
                      </div>
                    </div>
                    <div class="form-row" v-for="(item, index) in items">
                      <div class="form-group col-md-3">
                        <label>Mã hàng <sup class="text-danger">*</sup></label>
                        <v-select 
                        placeholder="chọn sản phẩm"
                        :options="products_data" 
                        :get-option-label="option => option.ProductsName+'/'+option.ProductsNumber+'(Đơn vi:'+option.ProductsUnit+')'"
                        :reduce="product => product.ProductsId" 
                        class="form-control"
                        name="ProductsId[]"
                        required
                        v-model="item.ProductsId"
                        @input="onProductsChange(index)">
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
                        <label>Số lượng <sup class="text-danger">*</sup></label>
                        <input type="number" required name="ProductsQty[]" v-model="item.ProductsQty" class="form-control">
                      </div>
                      <div class="form-group col-md-3">
                        <label>Đơn giá</label>
                        <input type="number" name="ProductsUnitPrice[]" v-model="item.ProductsUnitPrice" class="form-control" required>
                      </div>
                      <div class="form-group col-md-3">
                        <label>Thành tiền</label>
                        <input type="number" v-model="item.ProductsPrice" class="form-control">
                      </div>
                    </div>
                    <small class="mb-3"><a href="#" class="text-primary" @click="addNewItem()">Add more product</a> | <a href="#" class="text-danger" @click="removeLastItem()">Remove last product</a></small>
                    <div class="">
                      <button class="btn btn-primary float-right"><i class="fas fa-arrow-right"></i>&nbsp;Import</button>
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

  <script>
    $(function () {
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#import_spare_part',
        data: {
          items:[{ProductsId:'',ProductsQty: '', ProductsUnitPrice:''}],
          products_data: []
        },
        methods: {
          addNewItem(){
            this.items.push({ProductsId: null, ProductsQty: '', ProductUnitPrice: ''})
          },
          removeLastItem(){
            if(this.items.length == 1)
            {
              return;
            }
            this.items.splice(-1,1);
          },
          onProductsChange(index){
            
          }
        },
        created: function(){
          axios.get('/smes/spare-part/ajaxload.php').then(({data}) => {
            this.products_data = data['products_data'];
          }).catch(() => {
            console.log('error');
          });
        }
      })
    });
  </script>

</body>

</html>
