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
$newDB->where('ImportsStatus', 0);
$n_submit_import = $newDB->getOne('Imports');

if($n_submit_import){
    $arr['ImportsId'] = $n_submit_import['ImportsId'];
    $arr['ImportsPO'] = $n_submit_import['ImportsPO'];
    $arr['ImportsDocNo'] = $n_submit_import['ImportsDocNo'];
    $arr['SuppliersId'] = $n_submit_import['SuppliersId'];
    $arr['ImportsDate'] = $n_submit_import['ImportsDate'];
    $arr['ImportsNote'] = $n_submit_import['ImportsNote'];

    $newDB->where('ImportsId', $n_submit_import['ImportsId']);
    $inputs = $newDB->get('Inputs');
    $arr['inputs'] = $inputs;

}else{
    $newDB->orderBy('ImportsId', 'desc');
    $last_import = $newDB->getOne('Imports','ImportsId');
    if($last_import){
        $last_import_id = $last_import['ImportsId'];
    }else{
        $last_import_id = 0;
    }
    
    $PO_number = 'SP'.date('Y').date('m').date('d').'_'.($last_import_id+1);
    $docno = $PO_number;
    
    $imports_data = ['ImportsPO' => $PO_number, 'ImportsDocNo' => $docno, 'SuppliersId' => 0];
    $import_id = $newDB->insert('Imports',$imports_data);

    $newDB->orderBy('ImportsId', 'desc');
    $last_import = $newDB->getOne('Imports');

    $arr['ImportsId'] = $import_id;
    $arr['ImportsPO'] =  $last_import['ImportsPO'];
    $arr['ImportsDocNo'] =  $last_import['ImportsDocNo'];
    $arr['SuppliersId'] =  $last_import['SuppliersId'];
    $arr['ImportsDate'] =  $last_import['ImportsDate'];
    $arr['ImportsNote'] =  $last_import['ImportsNote'];

    $arr['inputs'] = [];

    // echo $docno;
    // return;
}


$arr[] = $spare_arr;
echo json_encode($arr);
return;
?>