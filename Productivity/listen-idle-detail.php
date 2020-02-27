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
    $process_id = $_GET['id'];
    $newDB->where('ProcessDailyHistoryId', $process_id);
    $old_ids = array_column($newDB->get('processidledetail'),'ProcessIdleDetailId');
    $data = array_filter($_POST);
    $new_ids = array();
    foreach($data['ProcessIdleDetailId'] as $index => $ngid){
        if($ngid != 'new'){
            $new_ids[] = $ngid;
            $newDB->where('ProcessIdleDetailId', $ngid);
            $newDB->update('processidledetail', [
                'IdleId' => $data['IdleId'][$index],
                'ProcessIdleDetailAmount' => $data['ProcessIdleDetailAmount'][$index]
            ]);
        }else{
            $newDB->insert('processidledetail', [
                'ProcessDailyHistoryId' => $process_id,
                'IdleId' => $data['IdleId'][$index],
                'ProcessIdleDetailAmount' => $data['ProcessIdleDetailAmount'][$index]
            ]);
        }
    }

    //delete ng detail
    foreach($old_ids as $val){
        if(!in_array($val, $new_ids)){
            $newDB->where('ProcessIdleDetailId', $val);
            $newDB->delete('processidledetail');
        }
    }
	
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:idle-detail.php?id='.$process_id);