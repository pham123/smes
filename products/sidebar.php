    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../home/" Style='background-color:white;'>
        <!-- <div class="sidebar-brand-icon rotate-n-15">
          <img src="../img/hallalogo.png" alt="logo" height="45" >
        </div> -->
        <div class="sidebar-brand-text mx-3" Style='Color:#22356f;font-size: 3em'>PRINT</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-home"></i>
          <span>Home</span></a>
      </li>

      <!-- Divider -->
<?php

$arr = array(
  array('Products.php', 'fa-angle-right',$lang['Products']),
);
echo nav_item($lang['Company'],$arr);
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
