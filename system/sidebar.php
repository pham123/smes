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
  array('?tables_Users',$oDB->lang("Users")),
  array('?tables_Modules',$oDB->lang("Modules")),
  array('?tables_Access',$oDB->lang("Access")),
);
echo nav_item_collapsed($oDB->lang("Users"),'fa-folder',$arr,'Users');
?>
<?php
$arr = array(
  array('?tables_Company',$oDB->lang("Company")),
  array('?tables_Divisions',$oDB->lang("Divisions")),
  array('?tables_Teams',$oDB->lang("Teams")),
  array('?tables_Parts',$oDB->lang("Parts")),
  array('?tables_Section',$oDB->lang("Section")),
  array('?tables_AssemblyLine',$oDB->lang("AssemblyLine")),
  array('?tables_Stations',$oDB->lang("Stations")),
  array('?tables_Machines',$oDB->lang("Machines")),
  array('?tables_Models',$oDB->lang("Models")),
);
echo nav_item_collapsed($oDB->lang("Company"),'fa-folder',$arr,'Company');
?>
<?php
$arr = array(
  array('?tables_SupplyChainType',$oDB->lang('SupplyChainType')),
  array('?tables_SupplyChainObject',$oDB->lang('SupplyChainObject')),
  array('?tables_MaterialTypes',$oDB->lang("MaterialType")),
  array('?tables_Products',$oDB->lang("Products")),
);
echo nav_item_collapsed($oDB->lang('Products'),'fa-folder',$arr,'products');
?>

      <!-- Divider -->
<?php
$arr = array(
  array('?tables_Lang', 'fa-angle-right',$oDB->lang("Lang")),
  array('?tables_Shift', 'fa-angle-right',$oDB->lang("Shift")),
  array('?tables_Times', 'fa-angle-right',$oDB->lang("Times")),
  array('?tables_TraceStation', 'fa-angle-right',$oDB->lang("TraceStation")),
  array('?tables_TraceRoute', 'fa-angle-right',$oDB->lang("TraceRoute")),
  array('?tables_TraceRouteAssign', 'fa-angle-right',$oDB->lang("TraceRouteAssign")),
  array('?tables_LabelType', 'fa-angle-right',$oDB->lang("LabelType")),
  array('?tables_LabelCode', 'fa-angle-right',$oDB->lang("LabelCode")),
  array('?tables_UserAssignTraceStation', 'fa-angle-right',$oDB->lang("UserAssignTraceStation")),
  array('?tables_Categories', 'fa-angle-right',$oDB->lang("SparePartCategories")),

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
