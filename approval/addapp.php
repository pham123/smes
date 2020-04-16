<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/function.php');
require('../function/MysqliDb.php');
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$pagetitle = $user->module;
$page_css = '.vs__dropdown-toggle {
  border: 0px !important;
  margin-top: -4px;
} table#items{font-size: 14px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();

?>

</style>
<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">

  <?php //require('sidebar.php') ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php require('navbar.php') ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="row m-0 pb-4 w-100">
            <div class="col-md-9">
              <div class="row">
                <div class="col-md-12">
                  <h4 style="margin-bottom: 10px;"><i class="fas fa-plus-circle"></i> 새로운 요청/NEW PURCHASE REQUEST</h4>
                </div>
                <div class="col-md-12 mt-1">
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">PO:<sup style="color: red;">*</sup></span>
                      </div>
                      <v-select 
                        :options="pos_data" 
                        label="PurchaseOrdersNo" 
                        :reduce="po => po.PurchaseOrdersId" 
                        v-model="form.PurchaseOrdersId"
                        class="form-control"
                        @input="onPOChange()" />
                    </div>
                </div>
                <div class="col-md-3">
                  <div class="input-group">
                    <select v-model="form.IsUrgent" class="form-control">
                      <option value="1">Normal</option>
                      <option value="2">Urgent</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">Group&nbsp;<sup style="color: red;">*</sup></span>
                    </div>
                    <v-select 
                      :options="cashgroups_data" 
                      label="CashgroupsName" 
                      :reduce="cashgroup => cashgroup.CashgroupsId" 
                      v-model="form.CashgroupsId"
                      class="form-control"
                      @input="onCashgroupChange()" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">Số lần thanh toán</span>
                    </div>
                      <input type="number" class="form-control" v-model="form.PurchasePaymentsNum">
                  </div>
                </div>
                <div class="col-md-12">
                    <div class="input-group mb-1">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Title&nbsp;<sup style="color: red;">*</sup></span>
                      </div>
                       <input type="text" class="form-control" v-model="form.PurchasePaymentsTitle" placeholder="Input title request here">
                    </div>
                </div>
                <div class="col-md-3">
                   <strong>Payment date:</strong> 
                   <input type="date" v-model="form.PurchasePaymentsDate" class="form-control">
            
                </div>
                <div class="col-md-3">
                   <strong>Received Date:</strong>
                   <input type="date" v-model="form.PurchasePaymentsReceiveDate" class="form-control">
                </div>
                <div class="col-md-3">
                   <strong>Amount:</strong>
                   <input type="text" v-model="form.PurchasePaymentsAmount" class="form-control">
                </div>
                <div class="col-md-3">
                  <strong>Currency:</strong> 
                  <br>
                  <select name="currency" v-model="form.PurchasePaymentsCurrency" class="form-control">
                    <option value="VND">VND</option>
                    <option value="USD">USD</option>
                    <option value="KRW">KRW</option>
                    <option value="KRW">EUR</option>
                  </select>
                </div>
                <h5 class="col-md-12 mt-3 text-secondary" style="border-top: 1px dotted #333;">Items and supplier details</h5>
                <div class="col-md-12">
                  <table class="w-100 table table-sm table-bordered" id="items">
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
                      <tr v-for="(item,index) in items">
                        <td>{{index+1}}</td>
                        <td>{{item.ProductsName}}</td>
                        <td>{{item.ProductsNumber}}</td>
                        <td>{{item.ProductsUnit}}</td>
                        <td>{{item.PurchasesEta}}</td>
                        <td>{{item.PurchasesQty}}</td>
                        <td>{{item.PurchaseItemsUnitPrice.format()}}</td>
                        <td><strong>{{(item.PurchasesQty*item.PurchaseItemsUnitPrice).format()}}</strong></td>
                        <td>{{item.PurchasesRemark}}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="5" style="text-align: center;"><strong>금액 (TOTAL)</strong></th>
                        <th></th>
                        <th></th>
                        <th><strong>{{totalAmount().format()}}</strong></th>
                        <th></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="col-md-12 mt-5">
                  <table class="table table-sm table-bordered" cellpadding="5" cellspacing="0" style="border-collapse:collapse; width:100%">
                    <tbody>
                      <tr><td colspan="2" style="text-align:center"><strong>공급자 정보 (은행 송금 전용)/ Information of supplier ( Only for Bank Tranfer ) </strong></td></tr>
                      <tr><td class="w-25"><strong>회사 이름/ Company name </strong></td><td>{{supplier?supplier.SupplierName : '' }}</td></tr>
                      <tr><td><strong>은행 계좌/ Bank Account </strong></td><td>{{supplier.SupplierBankAccount}}</td></tr>
                      <tr><td><strong>은행 이름/ Bank name </strong></td><td>{{supplier.SupplierBankName}}</td></tr>
                      <tr><td><strong>분기/ Branch </strong></td><td>{{supplier.SupplierBankBranch}}</td></tr>
                    </tbody>
                  </table>
              </div>

              </div>
            </div>
            <div class="col-md-3">
              <h5>Line approval</h5>
              <div class="form-group" v-for="(line, index) in form.lines" :key="index">
                <v-select 
                :placeholder="'line ' + (index+1)"
                :options="users_data" 
                v-model="line.user_id"
                :get-option-label="option => (option.UsersFullName+'-'+option.SectionName+' '+option.PositionsName)"
                :reduce="user => user.UsersId" 
                class="form-control" />
              </div>
              <?php
                $newDB->where('ModulesName', 'approval');
                $forcedLines = explode(',', $newDB->getOne('modules')['ModulesForcedLine']);
                foreach($forcedLines as $line){
              ?>
                <div class="form-group">
                  <select class="form-control" disabled>
                    <option value=""><?php 
                    $user =  $newDB->where('u.UsersId', $line)
                    ->join('employees e','e.EmployeesId=u.EmployeesId', 'left')
                    ->join('section s', 'e.SectionId=s.SectionId', 'LEFT')
                    ->join('positions p', 'e.EmployeesPosition=p.PositionsId', 'LEFT')
                    ->getOne('users u');
                    echo $user['UsersFullName'].'-'.$user['SectionName'].' '.$user['PositionsName'];
                    ?></option>
                  </select>
                </div>
              <?php
                }
              ?>
              <div class="mb-1">
                <a class="text-info" style="font-size: 12px;" href="#" @click="addNewLine()"><i class="fas fa-plus"></i>&nbsp;Add new line</a>
                &nbsp;&nbsp;
                <a class="text-danger" style="font-size: 12px;"  href="#" @click="removeLastLine()"><i class="fas fa-times"></i>&nbsp;Remove last line</a>
              </div>

              <h5 class="mt-3">Attached files</h5>
              <p>1. Quotations: <strong><a v-if="this.PurchasesId" :href="'/smes/purchase/quotation/'+this.PurchasesId" target="_blank">view</a></strong></p>
              <p>2. PO scan:<strong><a v-if="this.PurchaseOrdersId" target="_blank" :href="'/smes/purchase/po/'+this.PurchaseOrdersId+'_'+this.PurchaseOrdersFileName">view</a></strong></p>

              <button class="btn btn-primary">submit</button>
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
  <script src="../js/vform.js"></script>

  <script>
    Number.prototype.format = function(n, x) {
      var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
      return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&.');
    };
    $(function(){
      const { Form } = window.vform;
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#content',
        data: {
          form: new Form({
            PurchaseOrdersId: '',
            IsUrgent: '1',
            PurchasePaymentsNum: '',
            PurchasePaymentsTitle: '',
            PurchasePaymentsCurrency: 'VND',
            PurchasePaymentsDate: '',
            PurchasePaymentsReceiveDate: '',
            PurchasePaymentsAmount: '',
            lines: []
          }),
          PurchasesId: '',
          PurchaseOrdersId: '',
          PurchaseOrdersFileName:'',
          items: [],
          supplier: {},
          users_data: [],
          pos_data: [],
          cashgroups_data: []
        },
        methods: {
          addNewLine(){
            this.form.lines.push({user_id: null, id: null});
          },
          removeLastLine(){
            this.form.lines.splice(this.form.lines.length -1, 1)
          },
          totalAmount(){
            let sum = 0;
            this.items.forEach((item) => {
              sum += item.PurchasesQty * item.PurchaseItemsUnitPrice;
            });
            return sum;
          },
          onPOChange(){
            if(this.form.PurchaseOrdersId && this.form.PurchaseOrdersId != ''){
              axios.get('/smes/approval/load-po-data.php?id='+this.form.PurchaseOrdersId).then(({data}) => {
                this.supplier = data['supplier'];
                this.items = data['items'];
                this.PurchasesId = data['PurchasesId'];
                this.PurchaseOrdersId = data['PurchaseOrdersId'];
                this.PurchaseOrdersFileName = data['PurchaseOrdersFileName'];
              }).catch(() => {
                console.log('error');
              });
            }else{
              this.supplier = {};
              this.items = [];
            }
          }
        },
        created: function(){
          axios.get('/smes/approval/addapp_load_data.php').then(({data}) => {
            this.users_data = data['users'];
            this.pos_data = data['pos'];
            this.cashgroups_data = data['cashgroups_data'];
          }).catch(() => {
            console.log('error');
          });
        },
        computed: {

        }
      });
    })
  </script>

</body>

</html>
