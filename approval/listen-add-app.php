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
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';
    // return;
    $app_data = [
        'PurchaseOrdersId' => $_POST['PurchaseOrdersId'],
        'IsUrgent' => $_POST['IsUrgent'],
        'CashgroupsId' => $_POST['CashgroupsId'],
        'PurchasePaymentsNum' => $_POST['PurchasePaymentsNum'],
        'PurchasePaymentsTitle' => $_POST['PurchasePaymentsTitle'],
        'PurchasePaymentsDate' => $_POST['PurchasePaymentsDate'],
        'PurchasePaymentsReceiveDate' => $_POST['PurchasePaymentsReceiveDate'],
        'PurchasePaymentsAmount' => $_POST['PurchasePaymentsAmount'],
        'PurchasePaymentsCurrency' => $_POST['PurchasePaymentsCurrency'],
        'UsersId' => $_SESSION[_site_]['userid']
    ];
    $appid = $newDB->insert('purchasepayments', $app_data);
    if(isset($_POST['UsersId'])){

        $lines = $_POST['UsersId'];
        foreach($lines as $i => $l){
            $appla_data = [
                'PurchasePaymentsId' => $appid,
                'UsersId' => $l
            ];
            if($i == 0){
                $appla_data['LineStatus'] = 1;
            }
            $newDB->insert('purchasepaymentlineapproval', $appla_data);
        }
        //forced line;
        $newDB->where('ModulesName', 'approval');
        $forcedLines = explode(',', $newDB->getOne('modules')['ModulesForcedLine']);
    }
    foreach ($forcedLines as $key => $value) {
        $line_data = [
            'PurchasePaymentsId' => $appid,
            'UsersId' => $value
        ];
        if(!isset($_POST['UsersId']) && $key == 0){
            $line_data['LineStatus'] = 1;
        }
        $newDB->insert('purchasepaymentlineapproval', $line_data);
    }
	
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:index.php');