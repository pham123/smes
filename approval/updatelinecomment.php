<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);


$id = $_GET['id'];

$data = json_decode(file_get_contents('php://input'), true);

$newDB->where('id', $id);
$newDB->update('purchasepaymentlineapproval', ['LineComment' => $data['comment']]);

?>