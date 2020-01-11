<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[] = "CREATE TABLE PatrolItems (
    PatrolItemsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    PatrolItemsName VARCHAR(255) NOT NULL
    );";
$sql[] = "INSERT INTO PatrolItems(PatrolItemsName) 
        VALUES
        ('Basic order(Working time, uniform, appearance)'),
        ('5S3R(5S3R, Visual managemen, red tag)'),
        ('ESH(Safety, environment, health)'),
        ('Sub production(Raw material, mold breakdown, repairing, technology)'),
        ('Process loss(Motion, Foolproof, MFA, Re-handling)'),
        ('Logictics loss(FIFO, stock, in-house transportation, unloading, loading)'),
        ('6 tools(defect, self/sequence check, inspection motion, quality process)'),
        ('Equipment loss(Machine trouble, maintenance, spare part)'),
        ('GD patrol(GD patrol)'),
        ('Other(Other)');";
$sql[] = "CREATE TABLE PatrolLosses (
    PatrolLossesId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    PatrolLossesName VARCHAR(255) NOT NULL
    );";
$sql[] = "INSERT INTO PatrolLosses(PatrolLossesName) 
    VALUES
    ('N/A'),
    ('Vận chuyển - Transportation'),
    ('Tồn kho - Inventory'),
    ('Thao tác - Motion'),
    ('Chờ đợi - Waiting'),
    ('Thừa quy trình - Over production'),
    ('Sản xuất thừa - Over processing'),
    ('Hàng lỗi - Defects');";
$sql[] = "CREATE TABLE Patrols (
    PatrolsId INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    PatrolItemsId INT(9) NOT NULL,
    PatrolLossesId INT(9) NOT NULL,
    PatrolsLocation VARCHAR(255) NOT NULL,
    PatrolsContent TEXT NOT NULL,
    UsersId INT(9) NOT NULL,
    PatrolsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PatrolsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";
for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

