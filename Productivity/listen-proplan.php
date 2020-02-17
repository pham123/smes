<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/db_lib.php');
require('../function/MysqliDb.php');
require('../function/function.php');
$user = New Users();
$user->set($_SESSION[_site_]['userid']);
$user->module = basename(dirname(__FILE__));
check($user->acess());
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = array_filter($_POST);
    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';
    // return;
    $newDB->where('ProPlanDate',$data['ProPlanDate']);
    $newDB->where('TraceStationId', $data['TraceStationId']);
    $newDB->delete('proplan');
    $shifts = $newDB->get('shift');
    foreach($data['ProductsId'] as $index => $pid){
        foreach($shifts as $shift){
            $sh_data = $data['shift_'.$shift['ShiftId']];
            if(intval($sh_data[$index]) > 0){
                $plan = [
                    'TraceStationId' => $data['TraceStationId'],
                    'ProductsId' => $pid,
                    'ProPlanDate' => $data['ProPlanDate'],
                    'ShiftId' => $shift['ShiftId'],
                    'ProPlanQuantity' => $sh_data[$index]
                ];
                $newDB->insert('proplan',$plan);
            }
        }
    }
	
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:plan.php');