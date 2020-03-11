<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
require('../function/MysqliDb.php');

$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_);
$newDB->join('employees e', 'u.EmployeesId=e.EmployeesId', 'LEFT');
$newDB->join('section s', 'e.SectionId=s.SectionId', 'LEFT');
$newDB->join('positions p', 'e.EmployeesPosition=p.PositionsId', 'LEFT');
$arr['users'] = $newDB->get('users u');



echo json_encode($arr);
return;
?>