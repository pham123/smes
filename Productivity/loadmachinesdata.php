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

$newDB->where('pl.TraceStationId', $tracestationId);
$newDB->where('pl.ProPlanDate', $date);
$newDB->join('products p', 'p.ProductsId=pl.ProductsId', 'left');
$arr['products'] = $newDB->get('proplan pl');

$newDB->where('TraceStationId', $tracestationId);
$newDB->join('machines m', 'am.MachinesId = m.MachinesId', "LEFT");
$arr['machines'] = $newDB->get('assignmachines am', null, 'm.MachinesId,m.MachinesName');

echo json_encode($arr);
return;
?>