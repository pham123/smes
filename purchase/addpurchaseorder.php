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
$page_css='th,td{font-weight: normal;font-size: 13px;text-align: center;vertical-align: middle !important;}.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 200px;overflow: hidden;font-size: 13px;}.vs__dropdown-menu li{font-size: 14px;}input::placeholder{font-size: 14px;} .vmoney{width: 100px}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$shifts = $newDB->get('shift');
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
//handle post
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $purchaseitemsid = $_POST['PurchaseItemsId'];
  $purchaseitemsunitprice = $_POST['PurchaseItemsUnitPrice'];
  $po_data = array_filter($_POST);
  unset($po_data['PurchaseItemsId']);
  unset($po_data['PurchaseItemsUnitPrice']);
  if(isset($_POST['submitBtn'])){
    $po_data['PurchaseOrdersStatus'] = 1;
  }
  $newDB->where('PurchaseOrdersId', $_POST['PurchaseOrdersId']);
  $newDB->update('purchaseorders', $po_data);

  for ($i=0; $i <count($purchaseitemsid) ; $i++) { 
    $newDB->where('PurchaseItemsId', $purchaseitemsid[$i]);
    $newDB->update('purchaseitems',['PurchaseItemsUnitPrice' => $purchaseitemsunitprice[$i]]);
  }
  header('Location:addpurchaseorder.php?id='.$_POST['PurchasesId']);
  exit();
}

//handle get
if(isset($_GET['id'])){
  $newDB->where('PurchasesId', $_GET['id']);
  $purchase = $newDB->getOne('purchases');
  if(!$purchase){
    header('Location:../404.html');
    return;
  }
  if($purchase['UsersId'] != $_SESSION[_site_]['userid']){
    header('Location:../403.php');
    return;
  }

}else{
  header('Location:../404.html');
  return;
}
$suppliers = $newDB->get('supplychainobject');
$purchaseitems = $newDB->where('PurchasesId', $purchase['PurchasesId'])
->join('products p', 'p.ProductsId=pui.ProductsId')->get('purchaseitems pui',null,'p.ProductsNumber,p.ProductsName,p.ProductsUnit,pui.PurchaseItemsId,pui.PurchasesEta,pui.PurchasesRemark,pui.PurchasesQty,pui.PurchaseItemsUnitPrice');

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
          <h5 class="text-center"><strong>품의서(PURCHASE ORDER)</strong></h5>
          <form method="post">
            <input type="hidden" name="PurchaseOrdersId" v-model="PurchaseOrdersId">
            <input type="hidden" name="PurchasesId" value="<?php echo $_GET['id'] ?>">
            <table style="width: 90%;" class="mx-auto">
              <tr>
                <th>업체 (Supplier Name)</th>
                <td>
                  <select :readonly="!canEdit" class="form-control" style="width: 200px;" required v-model="SupplyChainObjectId" name="SupplyChainObjectId">
                  <option value="">select</option>
                  <?php 
                  foreach ($suppliers as $key => $value) {
                    echo "<option value='".$value['SupplyChainObjectId']."'>".$value['SupplyChainObjectName']."</option>";
                  }
                  ?>            
                  </select>
                </td>
                <th>PO 번호(No)</th>
                <td>
                  <input :readonly="!canEdit" type="text" class="form-control" v-model="PurchaseOrdersNo" name="PurchaseOrdersNo">
                </td>
              </tr>
              <tr>
                <th> 업체 코드(Supplier Code)</th>
                <td></td>
                <th>PO 날짜(Date)	</th>
                <td>
                  <input :readonly="!canEdit" type="date" class="form-control" v-model="PurchaseOrdersDate" name="PurchaseOrdersDate">
                </td>
              </tr>
              <tr>
                <th> 배송 방식(Shipment Method)</th>
                <td>
                  <input :readonly="!canEdit" type="text" class="form-control" v-model="PurchaseOrdersShipmentMethod" name="PurchaseOrdersShipmentMethod">
                </td>
                <th>통화(Currency)</th>
                <td>
                  <input :readonly="!canEdit" type="text" class="form-control" v-model="PurchaseOrdersCurrency" name="PurchaseOrdersCurrency">
                </td>
              </tr>
              <tr>
                <th> 인도 장소(Plate of discharge)</th>
                <td>
                  <input :readonly="!canEdit" type="text" class="form-control" v-model="PurchaseOrdersPlateDischarge" name="PurchaseOrdersPlateDischarge">
                </td>
                <th>지불방식(Payment Term)</th>
                <td>
                  <input :readonly="!canEdit" type="text" class="form-control" v-model="PurchaseOrdersPaymentTerm" name="PurchaseOrdersPaymentTerm">
                </td>
              </tr>
              <tr>
                <th> 페이지(Page)</th>
                <td>
                  <input :readonly="!canEdit" type="text" class="form-control" v-model="PurchaseOrdersPage" name="PurchaseOrdersPage">
                </td>
                <th>물동량(Moving Plan)</th>
                <td>
                  <input :readonly="!canEdit" type="text" class="form-control" v-model="PurchaseOrdersMovingPlan" name="PurchaseOrdersMovingPlan">
                </td>
              </tr>
            </table>
            <div class="w-100" class="mt-3" style="overflow: auto;">
              <table class="table table-bordered w-100">
                <thead>
                  <tr>
                    <th><strong>순번(No)</strong></th>
                    <th><strong>품명 (Part Name)</strong></th>
                    <th><strong>코드(Part No)</strong></th>
                    <th><strong>단위 (UNIT)</strong></th>
                    <th><strong>입고 (Need By Date)</strong></th>
                    <th><strong>수량 (Qty)</strong></th>
                    <th><strong>단가 (Price)</strong></th>
                    <th><strong>금액 (Amount)</strong></th>
                    <th><strong>비고(Note)</strong></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item,index) in purchaseitems">
                    <input type="hidden" name="PurchaseItemsId[]" :value="item.PurchaseItemsId">
                    <td>{{index+1}}</td>
                    <td>{{item.ProductsName}}</td>
                    <td>{{item.ProductsNumber}}</td>
                    <td>{{item.ProductsUnit}}</td>
                    <td>{{item.PurchasesEta}}</td>
                    <td>{{item.PurchasesQty}}</td>
                    <td><money v-model="item.PurchaseItemsUnitPrice" v-bind="money" class="vmoney" required :readonly="!canEdit"></money></td>
                    <input type="hidden" name="PurchaseItemsUnitPrice[]" :value="item.PurchaseItemsUnitPrice">
                    <td>{{(item.PurchasesQty*item.PurchaseItemsUnitPrice).format()}}</td>
                    <td>{{item.PurchasesRemark}}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="5"><strong>Total</strong></th>
                    <th></th>
                    <th></th>
                    <th>{{totalAmount.format()}}</th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <label style="font-size: 14px;"><strong>Suppliers</strong></label>
                <textarea readonly="!canEdit" name="PurchaseOrdersSupplierComment" v-model="PurchaseOrdersSupplierComment" cols="30" rows="4" class="form-control"></textarea>
              </div>
              <div class="col-md-6 d-flex">
                <button v-show="canEdit" name="saveBtn" class="btn btn-primary mr-1 mt-auto">Save</button>
                <button v-show="canEdit" name="submitBtn" class="btn btn-success mr-1 mt-auto">Submit</button>
                <a :href="'print-po.php?id='+PurchaseOrdersId" target="_blank" class="btn btn-secondary mt-auto"><i class="fas fa-print"></i></a>
              </div>
          </div>
          </form>
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
  <script type="module" src="../js/v-money.js">
    import money from '../js/v-money.js';
    Vue.use(money, {precision: 4});
  </script>

  <script>
    Number.prototype.format = function(n, x) {
      var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
      return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&.');
    };
    $(function () {
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#content',
        data: {
          PurchaseOrdersId: null,
          SupplyChainObjectId: '',
          PurchaseOrdersDate: null,
          PurchaseOrdersNo: '',
          PurchaseOrdersShipmentMethod: '',
          PurchaseOrdersCurrency: '',
          PurchaseOrdersPlateDischarge: '',
          PurchaseOrdersPaymentTerm: '',
          PurchaseOrdersPage: '',
          PurchaseOrdersMovingPlan: '',
          PurchaseOrdersSupplierComment: '',
          PurchaseOrdersStatus: 0,
          purchaseitems:<?php echo json_encode($purchaseitems) ?>,
          money: {
            decimal: ',',
            thousands: '.',
            prefix: '',
            suffix: '',
            precision: 0,
            masked: false /* doesn't work with directive */
          }
        },
        methods: {
        },
        created: function(){
          axios.get('/smes/purchase/loadpurchaseorderdata.php?id='+<?php echo $_GET['id']?>).then(({data}) => {
            this.PurchaseOrdersId = data['PurchaseOrdersId'];
            this.SupplyChainObjectId = data['SupplyChainObjectId'];
            this.PurchaseOrdersNo = data['PurchaseOrdersNo'];
            this.PurchaseOrdersDate = data['PurchaseOrdersDate'];
            this.PurchaseOrdersShipmentMethod = data['PurchaseOrdersShipmentMethod'];
            this.PurchaseOrdersCurrency = data['PurchaseOrdersCurrency'];
            this.PurchaseOrdersPlateDischarge = data['PurchaseOrdersPlateDischarge'];
            this.PurchaseOrdersPaymentTerm = data['PurchaseOrdersPaymentTerm'];
            this.PurchaseOrdersPage = data['PurchaseOrdersPage'];
            this.PurchaseOrdersMovingPlan = data['PurchaseOrdersMovingPlan'];
            this.PurchaseOrdersSupplierComment = data['PurchaseOrdersSupplierComment'];
            this.PurchaseOrdersStatus = data['PurchaseOrdersStatus'];
          }).catch(() => {
            console.log('error');
          });
        },
        computed: {
          canEdit() {
            return this.PurchaseOrdersStatus == '0';
          },
          totalAmount() {
            sum = 0;
            this.purchaseitems.forEach((item) => {
              sum += item.PurchasesQty * item.PurchaseItemsUnitPrice;
            })
            return sum;
          }
        },
    });
    })
  </script>

</body>

</html>
