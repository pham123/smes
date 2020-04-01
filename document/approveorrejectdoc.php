<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require '../vendor/autoload.php';
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = $_POST;
  if(isset($_POST['approveBtn'])){
    $status = 2;
  }
  if(isset($_POST['rejectBtn'])){
    $status = 3;
  }
  $newDB->where('DocumentDetailId', $data['id']);
  $newDB->where('UsersId', $_SESSION[_site_]['userid']);
  $newDB->update('DocumentDetailLineApproval', [
    'DocumentDetailLineApprovalStatus' => $status,
    'DocumentDetailLineApprovalComment' => $data['comment']
  ]);
  if($status == 2){
    //find next line make it in process
    $newDB->where('DocumentDetailId', $data['id']);
    $newDB->where('DocumentDetailLineApprovalStatus', null, 'is');
    $newDB->orderBy('DocumentDetailLineApprovalId', 'asc');
    $newDB->update('DocumentDetailLineApproval', [
      'DocumentDetailLineApprovalStatus' => 1
    ],1);
    //send mail to next line
    $newDB->where('DocumentDetailLineApprovalStatus', 1);
    $nextLineApp = $newDB->getOne('documentdetaillineapproval');

    if($nextLineApp){
      $newDB->where('UsersId', $nextLineApp['UsersId']);
      $lineUser = $newDB->getOne('users');
      //Create a new PHPMailer instance
      $mail = new PHPMailer;
      //Tell PHPMailer to use SMTP
      $mail->isSMTP();
      //Enable SMTP debugging
      // SMTP::DEBUG_OFF = off (for production use)
      // SMTP::DEBUG_CLIENT = client messages
      // SMTP::DEBUG_SERVER = client and server messages
      $mail->SMTPDebug = SMTP::DEBUG_SERVER;
      configurePHPMailer($mail, 'Document Approval');
      //Set who the message is to be sent to
      $mail->addAddress($lineUser['UsersEmail'], $lineUser['UsersFullName']);
      //Set the subject line
      $mail->Subject = 'Document Approval';
      //Read an HTML message body from an external file, convert referenced images to embedded,
      $mail->Body = "
      <p>Dear </p>
      <p>New Document waiting your approval</p>
      <p><a href='localhost/smes/document/approveorrejectdoc.php?id=".$insert_id."'>Please follow this link and approval this request</a></p>
      ";
      //convert HTML into a basic plain-text alternative body
      // $mail->msgHTML(file_get_contents('email_template.html'), __DIR__);
      //Replace the plain text body with one created manually
      $mail->IsHTML(true);
      $mail->AltBody = '';
      //Attach an image file
      $mail->addAttachment('');
  
      //send the message, check for errors
      if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
        echo 'Message sent!';
      }
      header('Location:viewdocapp.php?id='.$data['id']);
      return;
    }else{
      //send email to emailist
      $document_detail = $newDB->where('DocumentDetailId', $data['id'])->getOne('documentdetail');
      $newDB->where('DocumentId', $document_detail['DocumentId']);
      $document = $newDB->getOne('document');
      $email_list = $document['DocumentEmailList'];
      if(strpos($email_list,',') !== false){
        $email_arr = explode($email_list, ',');
      }else{
        $email_arr = [$email_list];
      }

      $mail = new PHPMailer;
      //Tell PHPMailer to use SMTP
      $mail->isSMTP();
      //Enable SMTP debugging
      // SMTP::DEBUG_OFF = off (for production use)
      // SMTP::DEBUG_CLIENT = client messages
      // SMTP::DEBUG_SERVER = client and server messages
      $mail->SMTPDebug = SMTP::DEBUG_SERVER;
      configurePHPMailer($mail, 'Link '.$document['DocumentName']);
      //Set who the message is to be sent to
      foreach($email_arr as $email){
        $mail->addAddress($email, "");
      }
      //Set the subject line
      $mail->Subject = 'Link '.$document['DocumentName'];
      //Read an HTML message body from an external file, convert referenced images to embedded,
      $mail->Body = "
      <p><strong>".$document['DocumentName']."</strong></p>
      <p><a href='#'>Click this link to download the document</a></p>
      ";
      //convert HTML into a basic plain-text alternative body
      // $mail->msgHTML(file_get_contents('email_template.html'), __DIR__);
      //Replace the plain text body with one created manually
      $mail->IsHTML(true);
      $mail->AltBody = '';
      //Attach an image file
      $mail->addAttachment('');
  
      //send the message, check for errors
      if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
        echo 'Message sent!';
      }
      header('Location:viewdocapp.php?id='.$data['id']);
      return;
    }

  }
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id = safe($_GET['id']);
  $newDB->where('dd.DocumentDetailId', $id);
  $newDB->join('document d', 'd.DocumentId=dd.DocumentId', 'left');
  $newDB->join('section s', 's.SectionId=d.SectionId', 'left');
  $newDB->join('documenttype dt', 'dt.DocumentTypeId=d.DocumentTypeId');
  $thisdoc = $newDB->getOne('documentdetail dd');

  $filename = $thisdoc['DocumentDetailFileName'];
  $tmp = explode(".", $filename);
  $ext = end($tmp);

  $newDB->where('DocumentDetailId', $id);
  $newDB->where('UsersId', $_SESSION[_site_]['userid']);
  $newDB->where('DocumentDetailLineApprovalStatus', 1);
  $currentLine = $newDB->getOne('documentdetaillineapproval');
  if(!$thisdoc || !$currentLine){
    header('Location:../404.html');
    exit();
  }
}else{
  header('Location:index.php');
  exit();
}
$newDB->where('DocumentDetailId', $_GET['id']);
$lines = $newDB->get('documentdetaillineapproval');

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

          <h3 class="font-weight-bold"><?php echo $thisdoc['DocumentName']?></h3>
          <div class="row">
          <div class="col-md-6">
          <div class="row">
              <div class="col-md">
                  <span>Bộ phận quản lý: <strong><?php echo $thisdoc['SectionName']?></strong></span>
              </div>    
              <div class="col-md">
                  <span>Loại tài liệu: <strong><?php echo $thisdoc['DocumentTypeName']?></strong></span>
              </div>
          </div>

              <div class="row">
                  <div class="col-md">
                      <span>Miêu tả tài liệu</span>
                      <p class="text-body"><strong><?php echo $thisdoc['DocumentDescription']?></strong></p>
                  </div>
              </div>
              <div class="row">
                <div class="col-md">
                  <span>Version:</span>
                  <?php echo $thisdoc['DocumentDetailVersion']?>
                </div>
                <div class="col-md">
                  <span>Link:</span>
                  <a href="files/<?php echo $thisdoc['DocumentDetailId']?>.<?php echo $ext?>"><?php echo $thisdoc['DocumentDetailFileName']?></a>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <span>Version description:</span>
                  <p>
                    <?php echo $thisdoc['DocumentDetailDesc'] ?>
                  </p>
                </div>  
              </div>

              </div>


              <div class="col-md-6">
                <h5>Line comment</h5>
                <form method="post" action="">
                  <input type="hidden" name="id" value="<?php echo $id?>">
                <textarea name="comment" id="" class="form-control mb-1" rows="3"><?php echo $currentLine['DocumentDetailLineApprovalComment'] ?></textarea>
                <div class="d-flex justify-content-around">
                  <input type="submit" class="btn btn-success btn-sm w-25" name="approveBtn" value="Approve">
                  <input type="submit" class="btn btn-danger btn-sm w-25" name="rejectBtn" value="Reject">
                </div>
                </form>
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
                      <td><?php echo $thisdoc['DocumentCreateDate']?></td>
                      <td></td>
                    </tr>
                    <tr :key="index" v-for="(line,index) in form.lines">
                      <td class='text-center'>{{index+1}}</td>
                      <td>{{findUser(line.UsersId)?.UsersFullName}}</td>
                      <td>{{findUser(line.UsersId)?.SectionName}} {{findUser(line.UsersId)?.PositionsName}}</td>
                      <td :class='getStatus(line.DocumentDetailLineApprovalStatus)["class"]'>{{getStatus(line.DocumentDetailLineApprovalStatus)["text"]}}</td>
                      <td>{{line.DocumentDetailLineApprovalDate}}</td>
                      <td>
                        {{line.DocumentDetailLineApprovalComment}}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-12 mt-3">
                <h5>Email list</h5>
                <p><?php echo $thisdoc['DocumentEmailList']?></p>
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
          DocumentDetailId: <?php echo $_GET['id'];?>,
          SectionId: <?php echo $thisdoc['SectionId'].'';?>,
          UsersId: <?php echo $thisdoc['UsersId']?>,
          DocumentTypeId: <?php echo $thisdoc['DocumentTypeId']?>,
          DocumentSubmit: <?php echo $thisdoc['DocumentSubmit']?$thisdoc['DocumentSubmit']:0 ?>,
          EmailList: <?php echo json_encode($thisdoc['DocumentEmailList']?explode(",", $thisdoc['DocumentEmailList']):[]);?>,
          form: new Form({
            lines: <?php echo json_encode($lines)?>
          }),
          users_data: [],
          shifts_data: []
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
            return $result
          },
          updateComment(line){
            axios.post('updatelinecomment.php?id='+line.DocumentDetailLineApprovalId, {comment: line.DocumentDetailLineApprovalComment})
                  .then(({data}) => {
                    alert('comment updated!')
                  })
                  .catch((error) => {
                    alert('error');
                  })
          }
        },
        created: function(){
          axios.get('/smes/document/editdoc_load_data.php').then(({data}) => {
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
