   
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
          <span><?php echo $oDB->lang('NewIn-Out')?></span></a>
      </li>

      <!-- Divider -->
<?php
$arr = array(
  array('NewStockIn.php', 'fas fa-plus-square',$oDB->lang('Input')),
  array('NewStockOut.php', 'fas fa-plus-square',$oDB->lang('Output')),
);
echo nav_item($oDB->lang('InOut'),$arr);

$arr = array(
  array('StockIn.php', 'fas fa-plus-square',$oDB->lang('StockIn')),
  array('StockOut.php', 'fas fa-plus-square',$oDB->lang('StockOut')),
);
echo nav_item($oDB->lang('Stock'),$arr);

$arr = array(
  array('ReportOut.php', 'fas fa-plus-square',$oDB->lang('ReportOut')),
  array('ReportIn.php', 'fas fa-plus-square',$oDB->lang('ReportIn')),
);
echo nav_item($oDB->lang('Report'),$arr);

$arr = array(
  array('Material2.php', 'fas fa-plus-square',$oDB->lang('AddNew')),
);
echo nav_item($oDB->lang('Add'),$arr);
?>
 

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->
