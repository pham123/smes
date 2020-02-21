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
    // $newDB->where('TraceStationId', $data['TraceStationId']);
    // $newDB->delete('labelpattern');

    //pattern
    $newDB->where('TraceStationId', $data['TraceStationId']);
    $old_lpids = $newDB->get('labelpattern');

    $new_lp_ids = array();
    //update or insert label pattern
    foreach($data['ProductsId'] as $index => $pid){
        $lpid = $data['LabelPatternId'][$index];
        $pattern_data = [
            'TraceStationId' => $data['TraceStationId'],
            'ProductsId' => $pid,
            'LabelPatternValue' => $data['LabelPatternValue'][$index],
            'LabelPatternPackingStandard' => $data['LabelPatternPackingStandard'][$index]?$data['LabelPatternPackingStandard'][$index]:0
        ];
        if($lpid != 'new'){
            $newDB->where('LabelPatternId', $lpid);
            $newDB->update('labelpattern', $pattern_data);
            $new_lp_ids[] = $lpid;
        }else{
            $newDB->insert('labelpattern',$pattern_data);
        }
    }
    
    //delete label pattern
    foreach($old_lpids as $val){
        if(!in_array($val['LabelPatternId'], $new_lp_ids)){
            $newDB->where('LabelPatternId', $val['LabelPatternId']);
            $newDB->delete('labelpattern');
        }
    }

    //machines
    $newDB->where('TraceStationId', $data['TraceStationId']);
    $old_mc_ids = $newDB->get('assignmachines');
    $new_mc_ids = array();
    //update or insert label pattern
    foreach($data['MachinesId'] as $index => $mid){
        $amid = $data['AssignMachinesId'][$index];
        $machine_data = [
            'TraceStationId' => $data['TraceStationId'],
            'MachinesId' => $mid,
            'AssignMachinesDescription' => $data['AssignMachinesDescription'][$index]
        ];
        if($amid != 'new'){
            $newDB->where('AssignMachinesId', $amid);
            $newDB->update('assignmachines', $machine_data);
            $new_mc_ids[] = $amid;
        }else{
            $newDB->insert('assignmachines',$machine_data);
        }
    }
    
    //delete label pattern
    foreach($old_mc_ids as $val){
        if(!in_array($val['AssignMachinesId'], $new_mc_ids)){
            $newDB->where('AssignMachinesId', $val['AssignMachinesId']);
            $newDB->delete('assignmachines');
        }
    }
	
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:createpattern.php');