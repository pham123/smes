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

$newDB->where('ProcessDailyHistoryId', $arr[1]);
$process = $newDB->getOne('processdailyhistory');
if($process){
    $newDB->where('ProductsId', $process['ProductsId']);
    $newDB->where('MachinesId', $process['MachinesId']);
    $newDB->where('TraceStationId', $process['TraceStationId']);
    $newDB->where('ProcessDailyHistoryDate', $process['ProcessDailyHistoryDate']);
    $newDB->where('ProcessDailyHistoryMold', $process['ProcessDailyHistoryMold']);
    $newDB->where('PeriodId', $arr[2]);
    $temp = $newDB->getOne('processdailyhistory');
    if($temp){
        $newDB->where('ProductsId', $process['ProductsId']);
        $newDB->where('MachinesId', $process['MachinesId']);
        $newDB->where('TraceStationId', $process['TraceStationId']);
        $newDB->where('ProcessDailyHistoryDate', $process['ProcessDailyHistoryDate']);
        $newDB->where('ProcessDailyHistoryMold', $process['ProcessDailyHistoryMold']);
        $newDB->where('PeriodId', $arr[2]);
        $newDB->update('processdailyhistory', ['ProcessDailyHistory'.ucfirst($arr[0]) => $value,'LastUpdateUser' => $_SESSION[_site_]['userid']]);
    }else{
        $newDB->insert('processdailyhistory', [
            'ProcessDailyHistoryDate' => $process['ProcessDailyHistoryDate'],
            'ProductsId' => $process['ProductsId'],
            'ProcessDailyHistoryMold' => $process['ProcessDailyHistoryMold'],
            'MachinesId' => $process['MachinesId'],
            'TraceStationId' => $process['TraceStationId'],
            'PeriodId' => $arr[2],
            'ProcessDailyHistory'.ucfirst($arr[0]) => $value,
            'LastUpdateUser' => $_SESSION[_site_]['userid']
            ]);
    }
}

echo json_encode($temp);
return;

?>