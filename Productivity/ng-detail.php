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
$page_css='.vs__dropdown-toggle {border: 0px !important;margin-top: -8px;} .vs__selected{white-space: nowrap;max-width: 250px;overflow: hidden;font-size: 14px;}.form-group{margin-bottom: 0px;}';
require('../views/template-header.php');
require('../function/template.php');
$oDB = new db();
$process_id = $_GET['id'];
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

$newDB->where('ProcessDailyHistoryId', $process_id);
$newDB->join('machines m', 'proc.MachinesId = m.MachinesId', "LEFT");
$newDB->join('products p', 'proc.ProductsId = p.ProductsId', "LEFT");
$newDB->join('period per', 'proc.PeriodId = per.PeriodId', "LEFT");
$newDB->join('tracestation tr', 'proc.TraceStationId = tr.TraceStationId', "LEFT");
$process = $newDB->getOne('processdailyhistory proc');

$newDB->where('ProcessDailyHistoryId', $process_id);
$newDB->join('defectlist d', 'p.DefectListId = d.DefectListId', "LEFT");
$ng_details = $newDB->get('processngdetail p');

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
                <div class="card" id="app" process_id="<?php echo $process_id;?>">
                  <h5 class="card-header">NG details(<?php echo $process['ProcessDailyHistoryNg'];?>)<br><small class="font-weight-bold"><?php echo $process['ProcessDailyHistoryDate'].'-'.$process['TraceStationName'].'-'.$process['ProductsName'].'('.$process['ProductsNumber'].')'.'-'.$process['MachinesName'];?></small></h5>
                  <div class="card-body">
                    <form action="listen-ng-detail.php?id=<?php echo $process_id?>" method="post">
                      <div class="form-row" v-for="(item,index) in items">
                        <div class="form-group col-md-1"><label v-if="index ==0">#</label><p>{{item.ProcessNgDetailId}}</p></div>
                        <input type="hidden" name="ProcessNgDetailId[]" v-model="item.ProcessNgDetailId">
                        <div class="form-group col-md-6">
                          <label v-if="index == 0">Defect Name</label>
                          <select name="DefectListId[]" v-model='item.DefectListId' class="form-control" required>
                            <?php 
                            $s = $oDB->sl_all('defectlist',1);
                            echo "<option value=''>defect</option>";
                            foreach ($s as $key => $value) {
                              echo "<option value='".$value['DefectListId']."'>".$value['DefectListName']."</option>";
                            }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label v-if="index == 0">Quantity</label>
                          <input type="number" class="form-control" required name="ProcessNgDetailQty[]" v-model="item.ProcessNgDetailQty">
                        </div>
                        <div class="form-group col-md-1">
                          <label v-if="index==0" style="font-size: 14px;">Remove</label>
                          <a href="#" @click="removeItem(index)" class="d-block"><i style="margin-top: 5px;" class="text-danger fas fa-times"></i></a>
                        </div>
                      </div>
                      <p class="text-danger" v-if="!validState">Number of defect not match(current: {{totalNg}}, total: {{ng_number}})</p>
                      <small class="d-block my-3"><a v-show="totalNg < ng_number" href="#" class="text-primary" @click="addNewItem()"><i class="fas fa-plus"></i> Add new defect</a></small>
                      <div class="">
                        <input v-show="totalNg == ng_number" class="btn btn-sm btn-primary float-right" type="submit" value="Save" />
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
          process_id: <?php echo $process_id ?>,
          ng_number: <?php echo $process['ProcessDailyHistoryNg'] ?>,
          items:<?php echo json_encode($ng_details) ?>,
        },
        methods: {
          addNewItem(){
            this.items.push({ProcessNgDetailId: 'new',DefectListId: '', ProcessNgDetailQty: '0'})
          },
          removeLastItem(){
            if(this.items.length == 0)
            {
              return;
            }
            this.items.splice(-1,1);
          },
          removeItem(index){
            if(this.items.length == 0)
            {
              return;
            }
            this.items.splice(index,1);
          },
          loadPlans(){
            if(this.TraceStationId && this.ProPlanDate){
              axios.get('/smes/productivity/loadplanajax.php?tracestationid='+this.TraceStationId+'&date='+this.ProPlanDate).then(({data}) => {
                console.log(data);
                this.plans = data['plans'];
              }).catch(() => {
                console.log('error');
              });
            }else{
              console.log('station or date not select');
            }
          }
        },
        created: function(){
        },
        computed: {
          validState: function () {
            return this.totalNg == this.ng_number;
          },
          totalNg: function(){
            let totalNg=0;
            this.items.forEach((value,index) => {
              totalNg += parseInt(value.ProcessNgDetailQty);
            })
            return totalNg;
          }
        }
    });
    })
  </script>

</body>

</html>
