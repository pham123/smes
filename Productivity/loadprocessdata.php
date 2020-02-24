<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

// $newDB->where
$tracestationId = $_GET['tracestationid'];
$date = $_GET['date'];

$newDB->where('TraceStationId', $tracestationId);
$newDB->where('ProPlanDate', $date);
$newDB->join('products s','p.ProductsId = s.ProductsId', 'LEFT');
$products = $newDB->get('proplan p', null, 'distinct p.ProductsId,s.ProductsName,s.ProductsNumber');
$arr['products'] = $products;

$newDB->where('TraceStationId', $tracestationId);
$newDB->join('machines m', 'am.MachinesId = m.MachinesId', "LEFT");
$arr['machines'] = $newDB->get('assignmachines am', null, 'm.MachinesId,m.MachinesName');

$newDB->where('TraceStationParentId', $tracestationId);
$station_ids = array_column($newDB->get('tracestation', null, 'TraceStationId'), 'TraceStationId');
$station_ids[] = intval($tracestationId);

$newDB->where('ProcessDailyHistoryDate', $date);
$newDB->where('proc.TraceStationId', $station_ids, "IN");
$newDB->join('products p', 'p.ProductsId = proc.ProductsId', 'LEFT');
$newDB->join('machines m', 'proc.MachinesId = m.MachinesId', 'LEFT');
$newDB->join('tracestation t', 'proc.TraceStationId = t.TraceStationId', 'LEFT');
// $newDB->groupBy('proc.TraceStationId,ProcessDailyHistoryDate,proc.MachinesId,proc.ProductsId');
$arr['processes'] = $newDB->get('processdailyhistory proc');

$newDB->where('ProcessDailyHistoryDate', $date);
$newDB->where('proc.TraceStationId', $station_ids, "IN");
$newDB->join('products p', 'p.ProductsId = proc.ProductsId', 'LEFT');
$newDB->join('machines m', 'proc.MachinesId = m.MachinesId', 'LEFT');
$newDB->join('tracestation t', 'proc.TraceStationId = t.TraceStationId', 'LEFT');
$newDB->groupBy('proc.TraceStationId,ProcessDailyHistoryDate,proc.MachinesId,proc.ProductsId');
$arr['processes_uniq'] = $newDB->get('processdailyhistory proc');



echo json_encode($arr);
return;
?>