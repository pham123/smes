<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <?php if (isset($refresh)) {
    echo "<meta http-equiv='refresh' content='".$refresh."'>";
  }?>
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="../img/halla.png" />
  <meta name="description" content="">
  <meta name="author" content="">
  <title><?php echo $pagetitle ?></title>
  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/country-picker-flags/css/countrySelect.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="stylesheet" href="../vendor/select/dist/css/bootstrap-select.min.css">
  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.css" rel="stylesheet">
  <link href="../css/custom.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <?php
  if(isset($has_chart)){
 
  echo '<link href="../css/highchart.css" rel="stylesheet">';

  }
  if(isset($has_fixedcolumn) && $has_fixedcolumn == true){
 
    echo '<link href="../css/fixedcolumn.css" rel="stylesheet">';
  
    }
  ?>
  <style>
  <?php
  if(isset($page_css)){
    echo $page_css;
  }
  ?>
  tr,td{
    color:black;
  }
  div{
    /* margin:5px 0px; */
  }
  th, td { 
    
    /* background-color:#F8F9FC; */
  }
  /* #dtfix {
    white-space: nowrap; 
  } */
    /* div.dataTables_wrapper {
        width: 1000px;
        margin: 0 auto;
    } */
  </style>
</head>