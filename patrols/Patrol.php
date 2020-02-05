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
require('./template_header.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
if(isset($_SESSION[_site_]['userlang'])){
  $oDB->lang = ucfirst($_SESSION[_site_]['userlang']);
}
?>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary static-top">
    <div class="container">
      <a class="navbar-brand" href="/smes/home"><img src="../img/hallalogo1.png" alt="logo" height="45" >Line Patrol</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">patrol list
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="patrol.php">add patrol</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">top</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">summary</a>
          </li>
          <span id="country_selector"></span>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container" id="add_patrol">
    <form method="post" action="listen-update-patrol.php" enctype="multipart/form-data">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h2 class="mt-3">Add Patrol</h2>
      </div>
      <div class="col-md-4">
        <p class="mb-0"><?php echo $oDB->lang('PatrolItems') ?> <sup class="text-danger">*</sup></p>
        <select name="PatrolItemsId" class="form-control" required v-model="PatrolItemsId">
          <?php 
          $pis = $oDB->sl_all('PatrolItems',1);
          echo "<option value=''>Hạng mục</option>";
          foreach ($pis as $key => $value) {
            echo "<option value='".$value['PatrolItemsId']."'>".$value['PatrolItemsName'].'('.$value['PatrolItemsDescription'].')'."</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-4">
        <p class="mb-0"><?php echo $oDB->lang('Area') ?> <sup class="text-danger">*</sup></p>
        <select name="AreasId" id="" class="form-control" required v-model="AreasId">
          <?php 
          $areas = $oDB->sl_all('Areas',1);
          echo "<option value=''>Khu vực</option>";
          foreach ($areas as $key => $value) {
            echo "<option value='".$value['AreasId']."'>".$value['AreasName']."</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-4">
        <p class="mb-0"><?php echo $oDB->lang('PatrolLoss') ?> <sup class="text-danger">*</sup></p>
        <select name="PatrolLossesId" class="form-control" required v-model="PatrolLossesId">
          <?php 
          $ptls = $oDB->sl_all('PatrolLosses',1);
          echo "<option value=''>Lãng phí</option>";
          foreach ($ptls as $key => $value) {
            echo "<option value='".$value['PatrolLossesId']."'>".$value['PatrolLossesName']."</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-12">
        <p class="mb-0"><?php echo $oDB->lang('PatrolsLocation') ?></p>
        <input v-model="PatrolsLocation" type="text" class="form-control" name="PatrolsLocation" placeholder="VD: Tủ điện - máy 650#2">
      </div>
      <div class="col-md-12">
        <p class="mb-0"><?php echo $oDB->lang('PatrolContent') ?></p>
        <textarea v-model="PatrolsContent" name="PatrolsContent" class="form-control" rows="4"></textarea>
        
      </div>
    </div>
    <div class="col-md-12 my-2 px-0">
      <v-select 
        placeholder="PIC"
        :options="users_data" 
        :get-option-label="option => option.UsersFullName"
        :reduce="user => user.UsersId" 
        class="form-control"
        required
        v-model="UsersId">
          <template #search="{attributes, events}">
            <input
              class="vs__search"
              :required="!UsersId"
              v-bind="attributes"
              v-on="events"
            />
          </template>
      </v-select>
      <input type="hidden" name="UsersId" :value="UsersId">
    </div>
    <div class="row">
      <div class="col-6">
        <div class="input-group-prepend">
            <span class="input-group-text" style="border-top-right-radius: 0px;border-bottom-right-radius:0px">Picture</span>
            <input type="file" accept="image/*" name="fileToUpload" data-buttontext="Find file" id="choose-file" class="form-control" style="padding-bottom: 5px;border-top-left-radius: 0;border-bottom-left-radius:0;line-height:1.3">
        </div>
        
        <input type="hidden" name="PatrolsId" :value="PatrolsId">
        <input type="hidden" name="PatrolsOption" value="1">
      </div>
      <div class="col-6" v-if="hasImg == '1'">
        <img :src="'/smes/patrols/image/small/'+this.PatrolsId+'.jpg'" alt="">
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-6">
        <input type="submit" name="saveBtn" class="btn btn-primary btn-block" value="save">
      </div>
      <div class="col-6">
        <input type="submit" name="sbBtn" class="btn btn-success btn-block" value="submit">
      </div>
    </div>
    </form>
  </div>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="../js/vuejs.min.js"></script>
  <script src="../js/axios.min.js"></script>
  
  <!-- use the latest vue-select release -->
  <script src="../js/vue-select.js"></script>
  <link rel="stylesheet" href="../css/vue-select.css">

  <!-- lang chooser -->
  <script src="../vendor/country-picker-flags/js/countrySelect.js"></script>
  <script>
     $(document).on('test', function(e,code){
      $.ajax({
        url: 'ajaxupdatelang.php?code='+code,
        type: 'get',
        success: function(){
          location.reload(true);
        }
      })
    });

    $("#country_selector").countrySelect({
      onlyCountries: ['en','vi','kr','cn'],
      preferredCountries: []
    });
    $('.country-list').css('overflow','hidden');
    $("#country_selector").countrySelect("selectCountry",<?php echo json_encode($oDB->lang); ?>);

  </script>

  <script>
    $(function () {
      Vue.component('v-select', VueSelect.VueSelect);
      new Vue({
        el: '#add_patrol',
        data: {
          users_data:[],
          PatrolsId: '',
          PatrolItemsId: '',
          AreasId: '',
          PatrolLossesId: '',
          PatrolsLocation: '',
          PatrolsContent: '',
          UsersId: null,
          hasImg: '',
        },
        methods: {
        },
        created: function(){
          axios.get('/smes/patrols/patrolajax.php').then(({data}) => {
            this.users_data = data['users_data'];
            this.PatrolsId = data['PatrolsId'];
            this.PatrolItemsId = (data['PatrolItemsId'] == 0) ? '' : data['PatrolItemsId'];
            this.AreasId = (data['AreasId'] == 0) ? '' : data['AreasId'];
            this.PatrolLossesId = (data['PatrolLossesId'] == 0) ? '' : data['PatrolLossesId'];
            this.PatrolsLocation = data['PatrolsLocation'];
            this.PatrolsContent = data['PatrolsContent'];
            this.UsersId = data['UsersId'];
            this.hasImg = data['hasImg'];
          }).catch((error) => {
            console.log(error);
          });
        }
      })
    });
  </script>

</body>

</html>
