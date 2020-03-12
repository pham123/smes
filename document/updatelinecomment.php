<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$periods = array_column($newDB->get('period', null, 'PeriodId'), 'PeriodId');


$id = $_GET['id'];

$data = json_decode(file_get_contents('php://input'), true);

$newDB->where('DocumentLineApprovalId', $id);
$newDB->update('documentlineapproval', ['DocumentLineApprovalComment' => $data['comment']]);

?>