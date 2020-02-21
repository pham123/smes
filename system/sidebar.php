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
        <a class="nav-link" href="../<?php $user->module ?>">
          <i class="fas fa-fw fa-home"></i>
          <span><?php echo $oDB->lang("Home") ?></span></a>
      </li>
<?php
$arr = array(
  array('index.php?tables_Users',$oDB->lang("Users")),
  array('index.php?tables_Modules',$oDB->lang("Modules")),
  array('index.php?tables_Access',$oDB->lang("Access")),
);
echo nav_item_collapsed($oDB->lang("Users"),'fa-folder',$arr,'Users');
?>
<?php
$arr = array(
  array('index.php?tables_Company',$oDB->lang("Company")),
  array('index.php?tables_Divisions',$oDB->lang("Divisions")),
  array('index.php?tables_Teams',$oDB->lang("Teams")),
  array('index.php?tables_Parts',$oDB->lang("Parts")),
  array('index.php?tables_Section',$oDB->lang("Section")),
  array('index.php?tables_AssemblyLine',$oDB->lang("AssemblyLine")),
  array('index.php?tables_Stations',$oDB->lang("Stations")),
  array('index.php?tables_Machines',$oDB->lang("Machines")),
  array('index.php?tables_Models',$oDB->lang("Models")),
  array('index.php?tables_Areas',$oDB->lang("Areas")),
);
echo nav_item_collapsed($oDB->lang("Company"),'fa-folder',$arr,'Company');
?>
<?php
$arr = array(
  array('index.php?tables_SupplyChainType',$oDB->lang('SupplyChainType')),
  array('index.php?tables_SupplyChainObject',$oDB->lang('SupplyChainObject')),
  array('index.php?tables_MaterialTypes',$oDB->lang("MaterialType")),
  array('index.php?tables_Products',$oDB->lang("Products")),
);
echo nav_item_collapsed($oDB->lang('Products'),'fa-folder',$arr,'products');

$arr = array(
  array('index.php?tables_DefectType',$oDB->lang('DefectType')),
  array('index.php?tables_DefectList',$oDB->lang('DefectList')),
  array('index.php?tables_IdleType',$oDB->lang("IdleType")),
  array('index.php?tables_Idle',$oDB->lang("Idle")),
  array('index.php?tables_Period',$oDB->lang("Period")),
  array('index.php?tables_Machines',$oDB->lang("Machines"))
);
echo nav_item_collapsed($oDB->lang('Productivity'),'fa-folder',$arr,'productivity');

$arr = array(
  array('?tables_MemoReduce',$oDB->lang('MemoReduce')),
  // MemoApplicability
  array('?tables_MemoApplicability',$oDB->lang('MemoApplicability')),
);
echo nav_item_collapsed($oDB->lang('Memos'),'fa-folder',$arr,'Memos');

$arr = array(
  array('index.php?tables_Lang', 'fa-angle-right',$oDB->lang("Lang")),
  array('index.php?tables_Shift', 'fa-angle-right',$oDB->lang("Shift")),
  array('index.php?tables_Times', 'fa-angle-right',$oDB->lang("Times")),
  array('index.php?tables_TraceStation', 'fa-angle-right',$oDB->lang("TraceStation")),
  array('index.php?tables_TraceRoute', 'fa-angle-right',$oDB->lang("TraceRoute")),
  array('index.php?tables_TraceRouteAssign', 'fa-angle-right',$oDB->lang("TraceRouteAssign")),
  array('index.php?tables_LabelType', 'fa-angle-right',$oDB->lang("LabelType")),
  array('index.php?tables_LabelCode', 'fa-angle-right',$oDB->lang("LabelCode")),
  array('index.php?tables_UserAssignTraceStation', 'fa-angle-right',$oDB->lang("UserAssignTraceStation")),
  array('index.php?tables_Categories', 'fa-angle-right',$oDB->lang("SparePartCategories")),
  array('index.php?tables_PatrolItems', 'fa-angle-right',$oDB->lang("PatrolItems")),
  array('index.php?tables_PatrolLosses', 'fa-angle-right',$oDB->lang("PatrolLosses")),

);
echo nav_item($oDB->lang("Company"),$arr);
?>
<!-- Divider -->
<hr class="sidebar-divider">
<!-- Heading -->



      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->
