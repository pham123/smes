<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();

$sql[0] = "CREATE TABLE Modules (
    ModulesId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ModulesName VARCHAR(30) NOT NULL UNIQUE,
    ModulesInformation VARCHAR(100) NOT NULL,
    ModulesDescription VARCHAR(100) NOT NULL,
    ModulesOption INT(1),
    ModulesCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    ModulesUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
    ";
$sql[1] = "
INSERT INTO Modules(`ModulesName`,`ModulesInformation`,`ModulesDescription`,`ModulesOption`)
VALUES('system','Admin Module','Test',1)";

$sql[2] = "CREATE TABLE Access (
    AccessId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ModulesId INT(6),
    UsersId INT(6),
    AccessOption INT(1),
    AccessCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    AccessUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );  
";

$sql[3] = "
INSERT INTO Access(`ModulesId`,`UsersId`,`AccessOption`)
VALUES(1,1,1)";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;

?>
