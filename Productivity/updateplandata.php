<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);

$data = json_decode(file_get_contents('php://input'), true);

$arr = explode('_',$data['name']);
$value = $data['value'];
if($value <= 0)
return;


$newDB->where('ProPlanId', $arr[2]);
$plan = $newDB->getOne('proplan');
if($plan){
    $newDB->where('TraceStationId', $plan['TraceStationId']);
    $newDB->where('ProductsId', $plan['ProductsId']);
    $newDB->where('ProPlanDate', $arr[1]);
    $newDB->where('ShiftId', $arr[3]);
    $temp = $newDB->getOne('proplan');
    if($temp){
        $newDB->where('TraceStationId', $plan['TraceStationId']);
        $newDB->where('ProductsId', $plan['ProductsId']);
        $newDB->where('ProPlanDate', $arr[1]);
        $newDB->where('ShiftId', $arr[3]);
        $newDB->update('proplan', ['ProPlanQuantity' => $value]);
        // $newDB->update('processdailyhistory', ['ProcessDailyHistory'.ucfirst($arr[0]) => $value,'LastUpdateUser' => $_SESSION[_site_]['userid']]);
    }else{
        $newDB->insert('proplan', [
            'TraceStationId' => $plan['TraceStationId'],
            'ProductsId' => $plan['ProductsId'],
            'ProPlanDate' => $arr[1],
            'ShiftId' => $arr[3],
            'ProPlanQuantity' => $value
            // 'LastUpdateUser' => $_SESSION[_site_]['userid']
            ]);
    }
}

echo json_encode($temp);
return;

?>