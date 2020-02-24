<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$periods = array_column($newDB->get('period', null, 'PeriodId'), 'PeriodId');


$date = $_GET['date'];

$data = json_decode(file_get_contents('php://input'), true);

$ok_arr = $data['ProcessDailyHistoryOk'];
$ng_arr = $data['ProcessDailyHistoryNg'];
$idle_arr = $data['ProcessDailyHistoryIdletime'];

$process_data = [
    'ProcessDailyHistoryDate' => $date,
    'ProductsId' => $data['ProductsId'],
    'ProcessDailyHistoryMold' => $data['ProcessDailyHistoryMold'],
    'MachinesId' => $data['MachinesId'],
    'TraceStationId' => $data['TraceStationId']
];

foreach($periods as $pid){
    $process_data['PeriodId'] = 0;

    if(isset($ok_arr[$pid]) && (intval($ok_arr[$pid]) > 0)){
        $process_data['ProcessDailyHistoryOk'] = $ok_arr[$pid];
        $process_data['PeriodId'] = $pid;
    }
    if(isset($ng_arr[$pid]) && (intval($ng_arr[$pid]) > 0)){
        $process_data['ProcessDailyHistoryNg'] = $ng_arr[$pid];
        $process_data['PeriodId'] = $pid;
    }
    if(isset($idle_arr[$pid]) && (intval($idle_arr[$pid]) > 0)){
        $process_data['ProcessDailyHistoryIdletime'] = $idle_arr[$pid];
        $process_data['PeriodId'] = $pid;
    }
    if($process_data['PeriodId'] != 0){
        $newDB->insert('processdailyhistory', $process_data);
    }
}
?>