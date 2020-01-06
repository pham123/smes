<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$newDB->where('ProductsOption', 4);
$spare_arr = $newDB->get('products',null,'ProductsId,ProductsNumber,ProductsName,ProductsUnit,ProductsStock');
$arr = [];
$arr['products_data'] = $spare_arr;

//FIND IMPORT NOT SUBMITTED
$newDB->where('ExportsStatus', 0);
$n_submit_export = $newDB->getOne('Exports');

if($n_submit_export){
    $arr['ExportsId'] = $n_submit_export['ExportsId'];
    $arr['ExportsDocNo'] = $n_submit_export['ExportsDocNo'];
    $arr['SectionId'] = $n_submit_export['SectionId'];
    $arr['ExportsDate'] = $n_submit_export['ExportsDate'];
    $arr['ExportsNote'] = $n_submit_export['ExportsNote'];
    $arr['ExportsReceiver'] = $n_submit_export['ExportsReceiver'];

    $newDB->where('ExportsId', $n_submit_export['ExportsId']);
    $outputs = $newDB->get('Outputs');
    $arr['outputs'] = $outputs;

}else{
    $newDB->orderBy('ExportsId', 'desc');
    $last_export = $newDB->getOne('Exports','ExportsId');
    if($last_export){
        $last_export_id = $last_export['ExportsId'];
    }else{
        $last_export_id = 0;
    }
    
    $docno = 'ESP'.date('Y').date('m').date('d').'_'.($last_export_id+1);
    
    $exports_data = ['ExportsDocNo' => $docno, 'SectionId' => 0];
    $export_id = $newDB->insert('Exports',$exports_data);

    $newDB->orderBy('ExportsId', 'desc');
    $last_export = $newDB->getOne('Exports');

    $arr['ExportsId'] = $export_id;
    $arr['ExportsDocNo'] =  $last_export['ExportsDocNo'];
    $arr['SectionId'] =  $last_export['SectionId'];
    $arr['ExportsDate'] =  $last_export['ExportsDate'];
    $arr['ExportsNote'] =  $last_export['ExportsNote'];
    $arr['ExportsReceiver'] =  $last_export['ExportsReceiver'];

    $arr['outputs'] = [];

    // echo $docno;
    // return;
}


$arr[] = $spare_arr;
echo json_encode($arr);
return;
?>