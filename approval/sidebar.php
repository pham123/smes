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
  $newDB->where('ppla.UsersId', $_SESSION[_site_]['userid']);
  $newDB->join('purchasepayments pp', 'ppla.PurchasePaymentsId=pp.PurchasePaymentsId', 'LEFT');
  $newDB->join('cashgroups c', 'pp.CashgroupsId=c.CashgroupsId','left');
  $newDB->join('purchaseorders po', 'po.PurchaseOrdersId=pp.PurchaseOrdersId', 'left');
  // $newDB->join('documenttype dt', 'dt.DocumentTypeId=d.DocumentTypeId', 'left');
  $newDB->groupBy('pp.PurchasePaymentsId,ppla.UsersId');
  return $newDB->get('purchasepaymentlineapproval ppla');
}

function waitingYourApproval(){
  global $newDB;
  $newDB->where('ppla.UsersId', $_SESSION[_site_]['userid'])
        ->where('ppla.LineStatus', 1);
  $newDB->join('purchasepayments pp', 'ppla.PurchasePaymentsId=pp.PurchasePaymentsId', 'LEFT');
  $newDB->join('cashgroups c', 'pp.CashgroupsId=c.CashgroupsId', 'LEFT');
  $newDB->join('purchaseorders po', 'pp.PurchaseOrdersId=po.PurchaseOrdersId', 'LEFT');
  $newDB->groupBy('pp.PurchasePaymentsId,ppla.UsersId');
  return $newDB->get('purchasepaymentlineapproval ppla');
}

function waitingFinalApproval(){
  global $newDB;
  $result = array();
  $receivedApps = totalReceived();
  foreach($receivedApps as $app){
    $newDB->where('PurchasePaymentsId', $app['PurchasePaymentsId']);
    $newDB->orderBy('id', 'DESC');
    $la = $newDB->getOne('purchasepaymentlineapproval');
    if($la['LineStatus'] == 1){
      array_push($result,$app);
    }
  }
  return $result;
}

function yourRejectedList(){
  global $newDB;
  $newDB->where('ppla.UsersId', $_SESSION[_site_]['userid'])
        ->where('ppla.LineStatus', 3);
  $newDB->join('purchasepayments pp', 'ppla.PurchasePaymentsId=pp.PurchasePaymentsId', 'LEFT');
  $newDB->join('cashgroups c', 'pp.CashgroupsId=c.CashgroupsId', 'LEFT');
  $newDB->join('purchaseorders po', 'po.PurchaseOrdersId=pp.PurchaseOrdersId', 'left');
  $newDB->groupBy('pp.PurchasePaymentsId,ppla.UsersId');
  return $newDB->get('purchasepaymentlineapproval ppla');
}

function yourCreatedApp(){
  global $newDB;
  $newDB->where('pp.UsersId', $_SESSION[_site_]['userid']);
  $newDB->join('cashgroups c', 'pp.CashgroupsId=c.CashgroupsId', 'left');
  $newDB->join('purchaseorders po', 'pp.PurchaseOrdersId=po.PurchaseOrdersId', 'left');
  return $newDB->get('purchasepayments pp');
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
  array('addapp.php', 'fas fa-plus-square',$oDB->lang('AddApp')),
  array('cashgroup.php', 'fas fa-list-ol',$oDB->lang('Cashgroup')),
);
echo nav_item($oDB->lang(''),$arr);
?>
<hr class="sidebar-divider d-none d-md-block">
      <li class="nav-item mt-2">
        <div id="collapsePages" class="collapse show" aria-labelledby="headingPages" data-parent="#accordionSidebar" style="">
          <div class="bg-white py-2 collapse-inner rounded">
            <ul style="list-style: none; padding-left: 10px;">
              <li><a class="" href="applist.php?type=1">Total received: <?php echo count(totalReceived())?></a></li>
              <li class="<?php if(count(waitingYourApproval())>0) echo 'bg-warning'?>"><a href="applist.php?type=2">Waiting your app: <?php echo count(waitingYourApproval()) ?></a></li>
              <li><a class="" href="applist.php?type=3">Waiting final app: <?php echo count(waitingFinalApproval()) ?></a></li>
              <li class="<?php if(count(yourRejectedList())>0) echo 'bg-danger'?>"><a href="applist.php?type=4">Your rejected list: <?php echo count(yourRejectedList()) ?></a></li>
              <li><a class="" href="applist.php?type=5">Your created app: <?php echo count(yourCreatedApp())?></a></li>
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
