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
        <a class="nav-link" style="padding: 5px 16px;" href="bom_index.php">
          <i class="fas fa-fw fa-list"></i>
          <span>BOM List</span></a>
      </li>

      <!-- Divider -->
<?php

$arr = array(
  array('index.php', 'fa-table',$oDB->lang('Products')),
  array('process_index.php', 'fa-table',$oDB->lang('Processes')),
  array('maker_index.php', 'fa-table',$oDB->lang('Makers')),
  array('classifiedmaterial_index.php', 'fa-table',$oDB->lang('ClassifiedMaterials')),
  array('machine_index.php', 'fa-table',$oDB->lang('Machines')),
);
echo nav_item($oDB->lang('Material'),$arr);
?>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->
