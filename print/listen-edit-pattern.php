<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
$oDB = new db();

$TraceStationId = safe($_POST['TraceStationId']);
$ProductsId = safe($_POST['ProductsId']);
$LabelPatternValue = safe($_POST['LabelPatternValue']);
$LabelPatternId = safe($_POST['LabelPatternId']);
$LabelPatternPackingStandard = safe($_POST['LabelPatternPackingStandard']);
//LabelPatternPackingStandard

// $sql = "INSERT INTO LabelPattern (`TraceStationId`,`ProductsId`,`LabelPatternValue`) VALUE (".$TraceStationId.",".$ProductsId.",'".$LabelPatternValue."')";

$field_values = "`TraceStationId`='".$TraceStationId."', `ProductsId`='".$ProductsId."', `LabelPatternPackingStandard`='".$LabelPatternPackingStandard."', `LabelPatternValue`='".$LabelPatternValue."'" ;
$oDB->update('LabelPattern',$field_values,'LabelPatternId='.$LabelPatternId);
$oDB=Null;
header('Location:editpattern.php?id='.$LabelPatternId);
