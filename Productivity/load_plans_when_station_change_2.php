<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

// $newDB->where
$tracestationId = $_GET['tracestationid'];
$date = date('Y-m');


// $newDB->where('TraceStationParentId', $tracestationId);
// $station_ids = array_column($newDB->get('tracestation', null, 'TraceStationId'), 'TraceStationId');
// $station_ids[] = intval($tracestationId);

$newDB->where('pl.ProPlanDate', $date.'%', 'LIKE');
$newDB->where('pl.TraceStationId', $tracestationId);
$newDB->join('products p', 'p.ProductsId = pl.ProductsId', 'LEFT');
$arr['plans'] = $newDB->get('proplan pl',null,'pl.*,p.ProductsName,p.ProductsNumber');

$newDB->where('pl.ProPlanDate', $date.'%', 'LIKE');
$newDB->where('pl.TraceStationId', $tracestationId);
$newDB->join('products p', 'p.ProductsId = pl.ProductsId', 'LEFT');
$newDB->groupBy('pl.ProductsId');
$arr['plans_uniq'] = $newDB->get('proplan pl', null, 'pl.*,p.ProductsName,p.ProductsNumber');



echo json_encode($arr);
return;
?>