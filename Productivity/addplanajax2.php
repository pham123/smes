<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

$date = $_GET['date'].'-00';
$stationId = $_GET['station'];

$data = json_decode(file_get_contents('php://input'), true);

$newDB->where('ProductsId', $data['ProductsId']);
$newDB->where('TraceStationId', $stationId);
$newDB->where('ProPlanDate', $_GET['date'].'-%', 'LIKE');
$temp = $newDB->getOne('proplan');
if($temp){
    return;
}
$plan_data = [
    'ProPlanDate' => $date,
    'ProductsId' => $data['ProductsId'],
    'TraceStationId' => $stationId,
    'ShiftId' => 0
];
$newDB->insert('proplan', $plan_data);

?>