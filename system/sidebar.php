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

      <!-- Divider -->
<?php

$arr = array(
  array('?tables_Lang', 'fa-angle-right',$oDB->lang("Lang")),
  array('?tables_Users', 'fa-angle-right',$oDB->lang("Users")),
  array('?tables_Company', 'fa-angle-right',$oDB->lang("Company")),
  array('?tables_Divisions', 'fa-angle-right',$oDB->lang("Divisions")),
  array('?tables_Teams', 'fa-angle-right',$oDB->lang("Teams")),
  array('?tables_Parts', 'fa-angle-right',$oDB->lang("Parts")),
  array('?tables_Section', 'fa-angle-right',$oDB->lang("Section")),
  array('?tables_AssemblyLine', 'fa-angle-right',$oDB->lang("AssemblyLine")),
  array('?tables_Stations', 'fa-angle-right',$oDB->lang("Stations")),
  array('?tables_Machines', 'fa-angle-right',$oDB->lang("Machines")),
  array('?tables_Models', 'fa-angle-right',$oDB->lang("Models")),

  array('?tables_MaterialTypes', 'fa-angle-right',$oDB->lang("MaterialType")),
  array('?tables_Products', 'fa-angle-right',$oDB->lang("Products")),
  array('?tables_Shift', 'fa-angle-right',$oDB->lang("Shift")),
  array('?tables_Times', 'fa-angle-right',$oDB->lang("Times")),
  array('?tables_TraceStation', 'fa-angle-right',$oDB->lang("TraceStation")),
  array('?tables_TraceRoute', 'fa-angle-right',$oDB->lang("TraceRoute")),
  array('?tables_TraceRouteAssign', 'fa-angle-right',$oDB->lang("TraceRouteAssign")),
  array('?tables_LabelType', 'fa-angle-right',$oDB->lang("LabelType")),
  array('?tables_LabelCode', 'fa-angle-right',$oDB->lang("LabelCode")),
  array('?tables_UserAssignTraceStation', 'fa-angle-right',$oDB->lang("UserAssignTraceStation")),
  array('?tables_Modules', 'fa-angle-right',$oDB->lang("Modules")),
  array('?tables_Access', 'fa-angle-right',$oDB->lang("Access")),
);
echo nav_item($oDB->lang("Company"),$arr);
?>
<?php
// $arr = array(
//   array('#', 'fa-angle-right','Item 1'),
//   array('#', 'fa-angle-right','Item 2'),
// );
// echo nav_item('test',$arr);

// $arr = array(
//   array('#','Item 1'),
//   array('#','Item 2'),
//   array('#','Item 3'),
//   array('#','Item 4')
// );

// echo nav_item_collapsed($lang['CreateNew'],'fa-plus-circle',$arr,'Create_new');
// $arr = array(
//   array('#','Item 1'),
//   array('#','Item 2'),
//   array('#','Item 3'),
//   array('#','Item 4')
// );
// echo nav_item_collapsed('Admin','fa-plus-circle',$arr,'Admin');

// $arr = array(
//   array('#','Report 1'),
//   array('#','Report 2'),
//   array('#','Report 3'),
//   array('#','Report 4')
// );
// echo nav_item_collapsed('Report','fa-chart-area',$arr,'Report');
?>
<!-- Divider -->
<hr class="sidebar-divider">
<!-- Heading -->
<div class="sidebar-heading">
  Addons
</div>
<?php
$arr = array(
  array('#','Login'),
  array('#','Register'),
  array('#','Forgot Password'),
  array('../404.html','404 Page')
);
echo nav_item_collapsed('Pages','fa-folder',$arr,'Pages');
echo nav_item_one('charts.html','fa-chart-area','Chart');
echo nav_item_one('tables.html','fa-table','Tables') 
?>


      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->
