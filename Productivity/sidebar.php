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
  array('plan.php', 'fas fa-plus-square',$oDB->lang('AddPlan')),
  array('showplan.php', 'fas fa-plus-square',$oDB->lang('ViewPlan')),
  array('fi.php', 'fas fa-plus-square',$oDB->lang('FiReport')),
  array('daily.php', 'fas fa-plus-square',$oDB->lang('FiDailyReport')),
  array('process-daily-history.php', 'fas fa-history',$oDB->lang('ProcessDailyReport')),
  array('view-process-daily-history.php', 'fas fa-eye',$oDB->lang('ViewProcessHistory')),

);
echo nav_item($oDB->lang('Plan'),$arr);
?>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->
