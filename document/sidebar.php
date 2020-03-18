<?php
/*
null - not yet on the line
1 - waiting approval
2 - approve
3 - reject
*/
function totalReceived(){
  global $newDB;
  //find all document have current user in line
  $newDB->where('ddla.UsersId', $_SESSION[_site_]['userid']);
  $newDB->join('documentdetail dd', 'ddla.DocumentDetailId=dd.DocumentDetailId', 'LEFT');
  $newDB->join('document d', 'd.DocumentId=dd.DocumentId','left');
  $newDB->join('section s', 's.SectionId=d.SectionId', 'left');
  $newDB->join('documenttype dt', 'dt.DocumentTypeId=d.DocumentTypeId', 'left');
  $newDB->groupBy('dd.DocumentDetailId,ddla.UsersId');
  return $newDB->get('documentdetaillineapproval ddla');
}

function waitingYourApproval(){
  global $newDB;
  $newDB->where('ddla.UsersId', $_SESSION[_site_]['userid'])
        ->where('ddla.DocumentDetailLineApprovalStatus', 1);
  $newDB->join('documentdetail dd', 'ddla.DocumentDetailId=dd.DocumentDetailId', 'LEFT');
  $newDB->join('document d', 'dd.DocumentId=d.DocumentId', 'LEFT');
  $newDB->join('section s', 's.SectionId=d.SectionId', 'left');
  $newDB->join('documenttype dt', 'dt.DocumentTypeId=d.DocumentTypeId', 'left');
  $newDB->groupBy('dd.DocumentDetailId,ddla.UsersId');
  return $newDB->get('documentdetaillineapproval ddla');
}

function waitingFinalApproval(){
  global $newDB;
  $result = array();
  $receivedDocs = totalReceived();
  foreach($receivedDocs as $doc){
    $newDB->where('DocumentDetailId', $doc['DocumentDetailId']);
    $newDB->orderBy('DocumentDetailLineApprovalId', 'DESC');
    $dla = $newDB->getOne('documentdetaillineapproval');
    if($dla['DocumentDetailLineApprovalStatus'] == 1){
      array_push($result,$doc);
    }
  }
  return $result;
}

function yourRejectedList(){
  global $newDB;
  $newDB->where('ddla.UsersId', $_SESSION[_site_]['userid'])
        ->where('ddla.DocumentDetailLineApprovalStatus', 3);
  $newDB->join('documentdetail dd', 'ddla.DocumentDetailId=dd.DocumentDetailId', 'LEFT');
  $newDB->join('document d', 'dd.DocumentId=d.DocumentId', 'LEFT');
  $newDB->join('section s', 's.SectionId=d.SectionId', 'left');
  $newDB->join('documenttype dt', 'dt.DocumentTypeId=d.DocumentTypeId', 'left');
  $newDB->groupBy('dd.DocumentDetailId,ddla.UsersId');
  return $newDB->get('documentdetaillineapproval ddla');
}

function yourCreatedDoc(){
  global $newDB;
  $newDB->where('dd.UsersId', $_SESSION[_site_]['userid']);
  $newDB->join('document d', 'dd.DocumentId=d.DocumentId', 'left');
  $newDB->join('section s', 's.SectionId=d.SectionId', 'left');
  $newDB->join('documenttype dt', 'dt.DocumentTypeId=d.DocumentTypeId', 'left');
  return $newDB->get('documentdetail dd');
}
?>    
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../home/" Style='background-color:white;'>
        <div class="sidebar-brand-icon rotate-n-15">
          <img src="../img/hallalogo.png" alt="logo" height="45" >
        </div>
        
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-home"></i>
          <span><?php echo $oDB->lang('Home')?></span></a>
      </li>

      <!-- Divider -->
<?php

$arr = array(
  array('documenttype.php', 'fas fa-plus-square',$oDB->lang('DocumentType')),
  array('adddoc.php', 'fas fa-plus-square',$oDB->lang('AddDocument')),
  array('documentlist.php', 'fas fa-plus-square',$oDB->lang('DocumentList')),
  array('documentlistdetail.php', 'fas fa-plus-square',$oDB->lang('DocumentDetail')),
);
echo nav_item($oDB->lang('Document'),$arr);
?>
      <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Document approval</span>
        </a>
        <div id="collapsePages" class="collapse show" aria-labelledby="headingPages" data-parent="#accordionSidebar" style="">
          <div class="bg-white py-2 collapse-inner rounded">
            <ul style="list-style: none; padding-left: 10px;">
              <li><a class="" href="docapplist.php?type=1">Total received: <?php echo count(totalReceived())?></a></li>
              <li class="bg-warning"><a href="docapplist.php?type=2">Waiting your app: <?php echo count(waitingYourApproval()) ?></a></li>
              <li><a class="" href="docapplist.php?type=3">Waiting final app: <?php echo count(waitingFinalApproval()) ?></a></li>
              <li class="bg-danger"><a class="text-white" href="docapplist.php?type=4">Your rejected list: <?php echo count(yourRejectedList()) ?></a></li>
              <li><a class="" href="docapplist.php?type=5">Your created docs: <?php echo count(yourCreatedDoc())?></a></li>
            </ul>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->
