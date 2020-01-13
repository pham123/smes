<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$users_arr = $newDB->get('Users',null,'UsersId,UsersFullName');
$arr = [];
$arr['users_data'] = $users_arr;

$newDB->where('PatrolsStatus', 0);
$newDB->where('PatrolsCreator', $_SESSION[_site_]['userid']);
$n_submit_patrol = $newDB->getOne('Patrols');
if($n_submit_patrol){
    $arr['PatrolsId'] = $n_submit_patrol['PatrolsId'];
    $arr['PatrolItemsId'] = $n_submit_patrol['PatrolItemsId'];
    $arr['AreasId'] = $n_submit_patrol['AreasId'];
    $arr['PatrolLossesId'] = $n_submit_patrol['PatrolLossesId'];
    $arr['PatrolsLocation'] = $n_submit_patrol['PatrolsLocation'];
    $arr['PatrolsContent'] = $n_submit_patrol['PatrolsContent'];
    $arr['UsersId'] = $n_submit_patrol['UsersId'];
    if(file_exists('./image/'.$n_submit_patrol['PatrolsId'].'.jpg')){
        $arr['hasImg'] = 1;
    }
}else{
    $patrol_data = [];
    $patrol_data['PatrolItemsId'] = $arr['PatrolItemsId'] = 0;
    $patrol_data['AreasId'] = $arr['AreasId'] = 0;
    $patrol_data['PatrolLossesId'] = $arr['PatrolLossesId'] = 0;
    $patrol_data['PatrolsLocation'] = $arr['PatrolsLocation'] = '';
    $patrol_data['PatrolsContent'] = $arr['PatrolsContent'] = '';
    $patrol_data['UsersId'] = $arr['UsersId'] = 0;
    $patrol_data['PatrolsCreator'] = $_SESSION[_site_]['userid'];
    $patrol_data['PatrolsOption'] = 1;

    $patrol_id = $newDB->insert('Patrols', $patrol_data);
    $arr['PatrolsId'] = $patrol_id;
    $arr['hasImg'] = 0;

}

echo json_encode($arr);
return;
?>