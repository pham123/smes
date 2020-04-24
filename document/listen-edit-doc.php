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
    $id = $_GET['id'];
    $newDB->where('DocumentId', $id);
    $document_data = [
        'DocumentName' => $_POST['DocumentName'],
        'DocumentNumber' => $_POST['DocumentNumber'],
        'SectionId' => $_POST['SectionId'],
        'DocumentTypeId' => $_POST['DocumentTypeId'],
        'DocumentDescription' => $_POST['DocumentDescription']
    ];
    if(isset($_POST['DocumentEmailList'])){
        $email_list = $_POST['DocumentEmailList'];
        $document_data['DocumentEmailList'] = $email_list[0];
    }
    if(isset($_POST['submitBtn'])){
        $document_data['DocumentSubmit'] = 1;
    }
    $newDB->update('document', $document_data);
    if(isset($_POST['Lines'])){
        $newDB->where('DocumentId', $id);
        $newDB->delete('documentlineapproval');

        $lines = $_POST['Lines'];
        foreach($lines as $i => $l){
            $dla_data = [
                'DocumentId' => $id,
                'UsersId' => $l
            ];
            if($i == 0 && isset($_POST['submitBtn'])){
                $dla_data['DocumentLineApprovalStatus'] = 1;
            }
            $newDB->insert('documentlineapproval', $dla_data);
        }
    }
	
}else{
	header('Location:../404.html');
}

$newDB = Null;
header('Location:documentlist.php');