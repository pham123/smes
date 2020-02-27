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
    $old_ids = array_column($newDB->get('processngdetail'),'ProcessNgDetailId');
    $data = array_filter($_POST);
    $new_ids = array();
    foreach($data['ProcessNgDetailId'] as $index => $ngid){
        if($ngid != 'new'){
            $new_ids[] = $ngid;
            $newDB->where('ProcessNgDetailId', $ngid);
            $newDB->update('processngdetail', [
                'DefectListId' => $data['DefectListId'][$index],
                'ProcessNgDetailQty' => $data['ProcessNgDetailQty'][$index]
            ]);
        }else{
            $newDB->insert('processngdetail', [
                'ProcessDailyHistoryId' => $process_id,
                'DefectListId' => $data['DefectListId'][$index],
                'ProcessNgDetailQty' => $data['ProcessNgDetailQty'][$index]
            ]);
        }
    }

    //delete ng detail
    foreach($old_ids as $val){
        if(!in_array($val, $new_ids)){
            $newDB->where('ProcessNgDetailId', $val);
            $newDB->delete('processngdetail');
        }
    }
	
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:ng-detail.php?id='.$process_id);