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
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -4px;} .vs__selected{white-space: nowrap;max-width: 250px;overflow: hidden;font-size: 14px;}.form-group{margin-bottom: 0px;} table.mytable{width: 100%;} table.mytable th, table.mytable td{border: 1px solid #333;border-collapse: collapse;font-size: 14px;} table.mytable th{background-color: lightsalmon;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
if (isset($_GET['id'])&&is_numeric($_GET['id'])) {
  $id = safe($_GET['id']);
  $newDB->where('pp.PurchasePaymentsId', $id);
  $newDB->join('purchaseorders po', 'pp.PurchaseOrdersId=po.PurchaseOrdersId', 'left');
  $newDB->join('cashgroups c', 'c.CashgroupsId=pp.CashgroupsId', 'left');
  $thisapp = $newDB->getOne('purchasepayments pp');
}else{
  header('Location:index.php');
  exit();
}
$newDB->where('PurchasePaymentsId', $_GET['id']);
$lines = $newDB->get('purchasepaymentlineapproval');

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

        <div class="container-fluid">

          <h3 class="font-weight-bold"><?php echo $thisapp['PurchasePaymentsTitle']?></h3>
          <div class="row">
          <div class="col-md-6">
          <div class="row">
              <div class="col-md">
                  <span>PO No: <strong><?php echo $thisapp['PurchaseOrdersNo']?></strong></span>
              </div>    
              <div class="col-md">
                  <span>Cashgroup: <strong><?php echo $thisapp['CashgroupsName']?></strong></span>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md">
                <span>Date:</span>
                <?php echo $thisapp['PurchasePaymentsDate']?>
              </div>
              <div class="col-md">
                <span>Receive date:</span>
                <?php echo $thisapp['PurchasePaymentsReceiveDate']?>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md">
                <span>Urgent: <strong><?php echo $thisapp['IsUrgent']==0?'no':'yes'?></strong></span>
              </div>
              <div class="col-md">
                <span>Amount:</span>
                <?php echo number_format($thisapp['PurchasePaymentsAmount'],0,',','.')?>
              </div>
            </div>
              </div>


              <div class="col-md-6">
                <h5>Line approval</h5>
                
                <template v-if="form.lines.length > 0">
                  <table>

                  </table>
                  <div class="form-group mb-1 lines" v-for="(line, index) in form.lines" :key="index">
                
                  </div>
                </template>
                <table class="mytable">
                  <thead>
                    <tr>
                      <th style="width: 30px; text-align: center;">#</th>
                      <th>PIC</th>
                      <th>Position</th>
                      <th>Status</th>
                      <th>Time</th>
                      <th>Comment</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>&nbsp;</td>
                      <td>{{findUser(this.UsersId)?.UsersFullName}}</td>
                      <td>{{findUser(this.UsersId)?.SectionName}} {{findUser(this.UsersId)?.PositionsName}}</td>
                      <td class="bg-success text-white text-center">Created</td>
                      <td><?php echo $thisapp['PurchasePaymentsCreateDate']?></td>
                      <td></td>
                    </tr>
                    <tr :key="index" v-for="(line,index) in form.lines">
                      <td class='text-center'>{{index+1}}</td>
                      <td>{{findUser(line.UsersId)?.UsersFullName}}</td>
                      <td>{{findUser(line.UsersId)?.SectionName}} {{findUser(line.UsersId)?.PositionsName}}</td>
                      <td :class='getStatus(line.LineStatus)["class"]'>{{getStatus(line.LineStatus)["text"]}}</td>
                      <td>{{line.ApprovalDate}}</td>
                      <td v-if="line.UsersId == UsersId">
                        <textarea v-model="line.LineComment" rows="2">{{line.LineComment}}</textarea>
                        <button class="btn btn-sm btn-primary float-right" @click="updateComment(line)">Update</button>
                      </td>
                      <td v-else>
                        {{line.LineComment}}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
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

  <script src="../js/vform.js"></script>

  <script>
    $(function(){
      const { Form } = window.vform;
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#content',
        data: {
          PurchasePaymentsId: <?php echo $_GET['id'];?>,
          UsersId: <?php echo $thisapp['UsersId']?>,
          form: new Form({
            lines: <?php echo json_encode($lines)?>
          }),
          users_data: []
        },
        methods: {
          findUser($uid){
            return this.users_data.filter((value,index) => {
              return value['UsersId'] == $uid;
            })[0];
          },
          getStatus($statusVal){
            $result = [];
            if($statusVal == 3){
              $result['text'] = 'Rejected';
              $result['class'] = 'bg-danger text-white text-center';
            }else if($statusVal == 2){
              $result['text'] = 'Approved';
              $result['class'] = 'bg-success text-white text-center';
            }else if($statusVal == 1){
              $result['text'] = 'In process';
              $result['class'] = 'bg-warning text-center';
            }else{
              $result['text'] = '';
              $result['class'] = '';
            }
            return $result;
          },
          updateComment(line){
            axios.post('updatelinecomment.php?id='+line.id, {comment: line.LineComment})
                  .then(({data}) => {
                    alert('comment updated!')
                  })
                  .catch((error) => {
                    alert('error');
                  })
          }
        },
        created: function(){
          axios.get('/smes/approval/addapp_load_data.php').then(({data}) => {
            this.users_data = data['users'];
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
