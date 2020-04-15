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
$page_css='th,td{font-weight: normal;font-size: 13px;text-align: center;vertical-align: middle !important;}.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 200px;overflow: hidden;font-size: 13px;}.vs__dropdown-menu li{font-size: 14px;}input::placeholder{font-size: 14px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$shifts = $newDB->get('shift');
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
$sections = $newDB->get('section');
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
          <h4 class="text-center"><strong>PURCHASE REQUEST</strong></h4>
          <p class="text-center"><em>Yêu cầu mua hàng</em></p>
          <form action="listen-purchase.php" method="post">
            <input type="hidden" v-model="PurchasesId" name="PurchasesId">
            <div class="form-group row">
              <label style="font-size: 14px;" class="col-sm-2 col-form-label"><strong>Request Dept/</strong><em>Bp yêu cầu:</em></label>
              <div class="col-sm-2">
                <select class="form-control" required v-model="RequestSectionId" name="RequestSectionId">
                  <option value="">select</option>
                  <?php 
                  foreach ($sections as $key => $value) {
                    echo "<option value='".$value['SectionId']."'>".$value['SectionName']."</option>";
                  }
                  ?>            
                </select>
              </div>
              <label style="font-size: 14px;" class="col-sm-2 col-form-label"><strong>Using For/</strong><em>Công đoạn sd:</em></label>
              <div class="col-sm-2">
                <select class="form-control" required v-model="TraceStationId" name="TraceStationId">
                  <option value="">select</option>
                  <?php 
                  foreach ($tracestations as $key => $value) {
                    echo "<option value='".$value['TraceStationId']."'>".$value['TraceStationName']."</option>";
                  }
                  ?>            
                </select>
              </div>
              <label style="font-size: 14px;" class="col-sm-2 col-form-label"><strong>Request Date/</strong><em>Ngày:</em></label>
              <div class="col-sm-2">
                <input type="date" class="form-control" v-model="PurchasesDate" name="PurchasesDate">
              </div>
              <label style="font-size: 14px;" class="col-sm-2 col-form-label"><strong>Received Dept/</strong><em>Bp nhận:</em></label>
              <div class="col-sm-2">
                <p style="margin-top: .5em;">Purchase</p>
              </div>
              <label style="font-size: 14px;" class="col-sm-2 col-form-label"><strong>Urgent/</strong><em>Khẩn cấp:</em></label>
              <div class="col-sm-2">
                <select class="form-control" v-model="IsUrgent" name="IsUrgent">
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </div>
              <label style="font-size: 14px;" class="col-sm-2 col-form-label"><strong>PR No/</strong><em>Số PR:</em></label>
              <div class="col-sm-2">
                <input type="text" readonly class="form-control" v-model="PurchasesNo" name="PurchasesNo">
              </div>
            </div>
            <div class="w-100" style="overflow: auto;">
              <table class="table table-bordered" style="margin-bottom: 70px;">
                <thead>
                  <tr>
                    <th rowspan="2"><strong>NO</strong><br><em>STT</em></th>
                    <th rowspan="2" style="min-width: 320px;"><strong>HALLA'S CODE</strong><br>Mã hàng</th>
                    <th rowspan="2" style="min-width: 150px;"><strong>VIETNAMESE NAME</strong><br><em>Tên tiếng Việt</em></th>
                    <th rowspan="2" style="min-width: 150px;"><strong>ENGLISH NAME</strong><br><em>Tên tiếng Anh</em></th>
                    <th rowspan="2"><strong>PICTURE</strong><br><em>Hình ảnh</em></th>
                    <th colspan="5"><strong>SPECIFICATION</strong><br><em>Thông số kỹ thuật</em></th>
                    <th rowspan="2"><strong>QUANTITY</strong><br><em>Số lượng yc<em></th>
                    <th rowspan="2"><strong>CURRENT STOCK</strong><br><em>Tồn kho hiện tại</em></th>
                    <th rowspan="2"><strong>AVERAGE USING/MONTH</strong><br><em>Lượng sử dụng trung bình/tháng</em></th>
                    <th rowspan="2"><strong>UNIT</strong><br><em>Đơn vị</em></th>
                    <th rowspan="2"><strong>ETA</strong><br><em>Ngày cân hàng</em></th>
                    <th rowspan="2"><strong>REMAX</strong><br><em>Ghi chú</em></th>
                  </tr>
                  <tr>
                    <th><strong>Manufacturer's code</strong><br><em>Mã của NSX<em></th>
                    <th><strong>Size</strong><br><em>Kích thước<em></th>
                    <th><strong>Color</strong><br><em>Màu sắc<em></th>
                    <th><strong>Material</strong><br><em>Vật liệu<em></th>
                    <th><strong>Manufacturer/Original country</strong><br><em>Nhà sx/Xuất sứ<em></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item,index) in purchaseitems">
                    <td>{{index+1}}</td>
                    <td>
                      <v-select 
                        placeholder="chọn sản phẩm"
                        :options="products_data" 
                        :get-option-label="option => option.ProductsNumber+'-'+option.ProductsName"
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
                    <td>{{productSelected(item).ProductsName}}</td>
                    <td>{{productSelected(item).ProductsEngName}}</td>
                    <td><img v-if="item.ProductsId" :src="'../products/image/small/'+item.ProductsId+'.jpg'" style="max-width: 30px;"></td>
                    <td>{{productSelected(item).ProductsManufacturerCode}}</td>
                    <td>{{productSelected(item).ProductsSize}}</td>
                    <td>{{productSelected(item).ProductsColor}}</td>
                    <td>{{productSelected(item).ProductsMaterial}}</td>
                    <td>{{productSelected(item).ProductsManufacturerName}}</td>
                    <td><input type="number" v-model="item.PurchasesQty" style="width:60px;" name="PurchasesQty[]"></td>
                    <td>{{productSelected(item).ProductsStock}}</td>
                    <td><input type="number" v-model="item.PurchasesAverageUsing" name="PurchasesAverageUsing[]" style="width: 60px;"></td>
                    <td>{{productSelected(item).ProductsUnit}}</td>
                    <td><input type="date" v-model="item.PurchasesEta" name="PurchasesEta[]"></td>
                    <td><input type="text" v-model="item.PurchasesRemark" name="PurchasesRemark[]"></td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="10"><strong>Total</strong></th>
                    <th></th>
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
            <div class="row">
              <div class="col-sm-6">
                <label style="font-size: 14px;"><strong>Comment of Manager:</strong></label>
                <textarea name="PurchasesComment" v-model="PurchasesComment" cols="30" rows="4" class="form-control"></textarea>
              </div>
              <div class="col-md-6 d-flex">
                <button name="saveBtn" class="btn btn-primary mr-1 mt-auto">Save</button>
                <button name="submitBtn" class="btn btn-success mr-1 mt-auto">Submit</button>
                <a :href="'print-purchase.php?id='+PurchasesId" target="_blank" class="btn btn-secondary mt-auto"><i class="fas fa-print"></i></a>
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
          PurchasesId: null,
          RequestSectionId: '',
          TraceStationId: '',
          IsUrgent: '0',
          PurchasesDate: null,
          PurchasesNo: '',
          PurchasesComment: '',
          PurchasesStatus: 0,
          purchaseitems:[{
            ProductsId:'',
            PurchasesQty:'',
            PurchasesEta:'',
            PurchasesRemark:'',
            PurchasesAverageUsing: 0
          }],
          products_data: []
        },
        methods: {
          productSelected(item){
            let product = this.products_data.filter((p) => {return p.ProductsId == item.ProductsId});
            if(product.length == 0){
              return {
                ProductsId: '',
                ProductsName:'',
                ProductsEngName: '',
                ProductsSize: '',
                ProductsMaterial: '',
                ProductsManufacturerCode: '',
                ProductsManufacturerName: '',
                ProductsUnit: '',
                ProductsStock: ''
              };
            }
            return product[0];
          },
          addNewItem(){
            this.purchaseitems.push({
              ProductsId:'',
              PurchasesQty:'',
              PurchasesEta:'',
              PurchasesRemark:'',
              PurchasesAverageUsing: 0
            });
          },
          removeLastItem(){
            if(this.purchaseitems.length == 0)
            {
              return;
            }
            this.purchaseitems.splice(-1,1);
          },
          removeItem(index){
            if(this.purchaseitems.length == 0)
            {
              return;
            }
            this.purchaseitems.splice(index,1);
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
          axios.get('/smes/purchase/loadpurchasedata.php').then(({data}) => {
            this.products_data = data['products_data'];
            this.PurchasesId = data['PurchasesId'];
            this.RequestSectionId = data['RequestSectionId'];
            this.TraceStationId = data['TraceStationId'];
            this.IsUrgent = data['IsUrgent']+'';
            this.PurchasesDate = data['PurchasesDate'];
            this.PurchasesNo = data['PurchasesNo'];
            this.PurchasesComment = data['PurchasesComment'];
            this.purchaseitems = data['purchaseitems'];
          }).catch(() => {
            console.log('error');
          });
        }
    });
    })
  </script>

</body>

</html>
