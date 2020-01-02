<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
//header("Location: login.php");
include('config.php');
include('function/db_lib.php');

$oDB = new db();
$sql[0] = "CREATE TABLE Users (
    UsersId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UsersName VARCHAR(30) NOT NULL UNIQUE,
    UsersFullName VARCHAR(30) NOT NULL,
    UsersPassword VARCHAR(100) NOT NULL,
    UsersEmail VARCHAR(50),
    UsersOption INT(2),
    UsersCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    UsersUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";

$sql[1] = "
INSERT INTO Users(`UsersName`,`UsersFullName`,`UsersEmail`,`UsersPassword`,`UsersOption`)
VALUES('dongpx','Phạm Xuân Đồng','dongpx@hallavina.vn','".md5('dong')."',1)";

$sql[2] = "CREATE TABLE Company (
    CompanyId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    CompanyName VARCHAR(30) NOT NULL UNIQUE,
    CompanyInformation VARCHAR(100) NOT NULL,
    CompanyDescription VARCHAR(100) NOT NULL,
    CompanyOption INT(1),
    CompanyCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    CompanyUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";
 
 $sql[3] = "
 INSERT INTO Company(`CompanyName`,`CompanyInformation`,`CompanyDescription`,`CompanyOption`)
 VALUES('HEV','Halla Electronics Vina','Halla Electronics Vina',1)";

 $sql[4] = "CREATE TABLE Divisions (
    DivisionsId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    DivisionsName VARCHAR(30) NOT NULL UNIQUE,
    DivisionsInformation VARCHAR(100) NOT NULL,
    DivisionsDescription VARCHAR(100) NOT NULL,
    DivisionsOption INT(1),
    DivisionsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    DivisionsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql[5] = "
INSERT INTO Divisions(`DivisionsName`,`DivisionsInformation`,`DivisionsDescription`,`DivisionsOption`)
VALUES('PRD','PRD','PRD',1)";



 $sql[6] = "CREATE TABLE Teams (
 TeamsId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 TeamsName VARCHAR(30) NOT NULL UNIQUE,
 TeamsInformation VARCHAR(100),
 TeamsDescription VARCHAR(100),
 TeamsOption INT(1),
 TeamsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
 TeamsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );";

$sql[7] = "
INSERT INTO Teams(`TeamsName`,`TeamsInformation`,`TeamsDescription`,`TeamsOption`)
VALUES('PP-NC','PP-NC','PP-NC',1)";

 $sql[8] = "CREATE TABLE Parts (
 PartsId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 PartsName VARCHAR(30) NOT NULL UNIQUE,
 PartsInformation VARCHAR(100) NOT NULL,
 PartsDescription VARCHAR(100) NOT NULL,
 PartsOption INT(1),
 PartsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
 PartsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );";

$sql[9] = "
INSERT INTO Parts(`PartsName`,`PartsInformation`,`PartsDescription`,`PartsOption`)
VALUES('PP-NC','PP-NC','PP-NC',1)";

$sql[10] = "CREATE TABLE Section (
 SectionId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 SectionName VARCHAR(30) NOT NULL UNIQUE,
 SectionInformation VARCHAR(100) NOT NULL,
 SectionDescription VARCHAR(100) NOT NULL,
 SectionOption INT(1),
 SectionCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
 SectionUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );";

$sql[11] = "
INSERT INTO Section(`SectionName`,`SectionInformation`,`SectionDescription`,`SectionOption`)
VALUES('PP-NC','PP-NC','PP-NC',1)";

$sql[12] = "CREATE TABLE AssemblyLine (
    AssemblyLineId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    AssemblyLineName VARCHAR(30) NOT NULL UNIQUE,
    AssemblyLineInformation VARCHAR(100) NOT NULL,
    AssemblyLineDescription VARCHAR(100) NOT NULL,
    AssemblyLineOption INT(1),
    AssemblyLineCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    AssemblyLineUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql[13] = "
INSERT INTO AssemblyLine(`AssemblyLineName`,`AssemblyLineInformation`,`AssemblyLineDescription`,`AssemblyLineOption`)
VALUES('PP-NC','PP-NC','PP-NC',1)";

$sql[14] = "CREATE TABLE Stations (
 StationsId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 StationsName VARCHAR(30) NOT NULL UNIQUE,
 StationsInformation VARCHAR(100) NOT NULL,
 StationsDescription VARCHAR(100) NOT NULL,
 StationsOption INT(1),
 StationsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
 StationsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );";

$sql[15] = "
INSERT INTO Stations(`StationsName`,`StationsInformation`,`StationsDescription`,`StationsOption`)
VALUES('StationsName,'PP-NC','PP-NC',1)";

$sql[16] = "CREATE TABLE Machines (
 MachinesId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 MachinesName VARCHAR(30) NOT NULL UNIQUE,
 MachinesInformation VARCHAR(100) NOT NULL,
 MachinesDescription VARCHAR(100) NOT NULL,
 MachinesOption INT(1),
 MachinesCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
 MachinesUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );";

$sql[17] = "
INSERT INTO Machines(`MachinesName`,`MachinesInformation`,`MachinesDescription`,`MachinesOption`)
VALUES('MachinesName','PP-NC','PP-NC',1)";

$sql[18] = " CREATE TABLE Models (
 ModelsId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 ModelsName VARCHAR(30) NOT NULL UNIQUE,
 ModelsInformation VARCHAR(100) NOT NULL,
 ModelsDescription VARCHAR(100) NOT NULL,
 ModelsOption INT(1),
 ModelsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
 ModelsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );";

$sql[19] = "
INSERT INTO Models(`ModelsName`,`ModelsInformation`,`ModelsDescription`,`ModelsOption`)
VALUES('WM','Máy giặt','Washing machine',1)";

$sql[20] = "CREATE TABLE Products (
 ProductsId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 ProductsName VARCHAR(30) NOT NULL,
 ProductsNumber VARCHAR(100) NOT NULL UNIQUE,
 ProductsDescription VARCHAR(100) NOT NULL,
 ProductsOption INT(1),
 ModelsId INT(6),
 ProductsCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
 ProductsUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );";

$sql[21] = "
INSERT INTO Products(`ProductsName`,`ProductsNumber`,`ProductsDescription`,`ProductsOption`,`ModelsId`)
VALUES('Products','PP-NC','PP-NC',1,1)";

$sql[22] = "CREATE TABLE Shift (
 ShiftId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 ShiftName VARCHAR(30) NOT NULL UNIQUE,
 ShiftInformation VARCHAR(100) NOT NULL,
 ShiftDescription VARCHAR(100) NOT NULL,
 ShiftOption INT(1),
 ShiftCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
 ShiftUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );";
$sql[23] = "
INSERT INTO Shift(`ShiftName`,`ShiftInformation`,`ShiftDescription`,`ShiftOption`)
VALUES('Products','PP-NC','PP-NC',1)";

$sql[24] = "CREATE TABLE Times (
 TimesId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 TimesName VARCHAR(30) NOT NULL UNIQUE,
 TimesInformation VARCHAR(100) NOT NULL,
 TimesDescription VARCHAR(100) NOT NULL,
 TimesOption INT(1),
 TimesCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
 TimesUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );";

$sql[25] = "
INSERT INTO Times(`TimesName`,`TimesInformation`,`TimesDescription`,`TimesOption`)
VALUES('Products','PP-NC','PP-NC',1)";

$sql[26] = "CREATE TABLE TraceStation (
    TraceStationId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TraceStationName VARCHAR(30) NOT NULL UNIQUE,
    TraceStationInformation VARCHAR(100) NOT NULL,
    TraceStationDescription VARCHAR(100) NOT NULL,
    TraceStationOption INT(1),
    TraceStationCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    TraceStationUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql[27] = "
INSERT INTO TraceStation(`TraceStationName`,`TraceStationInformation`,`TraceStationDescription`,`TraceStationOption`)
VALUES('Products','PP-NC','PP-NC',1)";


$sql[28] = "CREATE TABLE TraceRoute (
    TraceRouteId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TraceRouteName VARCHAR(30) NOT NULL UNIQUE,
    TraceRouteInformation VARCHAR(100) NOT NULL,
    TraceRouteDescription VARCHAR(100) NOT NULL,
    TraceRouteOption INT(1),
    TraceRouteCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    TraceRouteUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql[29] = "
INSERT INTO TraceRoute(`TraceRouteName`,`TraceRouteInformation`,`TraceRouteDescription`,`TraceRouteOption`)
VALUES('Products','PP-NC','PP-NC',1)";


$sql[30] = "CREATE TABLE TraceRouteAssign (
    TraceRouteAssignId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TraceStationId INT(6),
    TraceRouteId INT(6),
    TraceRouteAssignDescription VARCHAR(100) NOT NULL,
    TraceRouteAssignOption INT(1),
    TraceRouteAssignCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    TraceRouteAssignUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
    ";


$sql[31] = "
INSERT INTO TraceRouteAssign(`TraceRouteAssignDescription`,`TraceRouteAssignOption`,`TraceStationId`,`TraceRouteId`)
VALUES('PP-NC',1,1,1)";


$sql[32] = "CREATE TABLE LabelType (
    LabelTypeId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    LabelTypeName VARCHAR(30) NOT NULL UNIQUE,
    LabelTypeInformation VARCHAR(100) NOT NULL,
    LabelTypeDescription VARCHAR(100) NOT NULL,
    LabelTypeOption INT(1),
    LabelTypeCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    LabelTypeUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
    ";

$sql[33] = "
INSERT INTO LabelType(`LabelTypeName`,`LabelTypeInformation`,`LabelTypeDescription`,`LabelTypeOption`)
VALUES('Products','PP-NC','PP-NC',1)";

$sql[34] = "CREATE TABLE LabelCode (
    LabelCodeId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    LabelCodeName VARCHAR(30) NOT NULL UNIQUE,
    LabelCodeInformation VARCHAR(100) NOT NULL,
    LabelCodeDescription VARCHAR(100) NOT NULL,
    LabelCodeOption INT(1),
    LabelCodeCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    LabelCodeUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
    ";
$sql[35] = "
INSERT INTO LabelCode(`LabelCodeName`,`LabelCodeInformation`,`LabelCodeDescription`,`LabelCodeOption`)
VALUES('Products','PP-NC','PP-NC',1)";

$sql[36] = "CREATE TABLE UserAssignTraceStation (
    UserAssignTraceStationId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UserAssignTraceStationName VARCHAR(30),
    UsersId INT(6),
    TraceStationId INT(6),
    UserAssignTraceStationOption INT(1),
    UserAssignTraceStationCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    UserAssignTraceStationUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
    ";

$sql[37] = "INSERT INTO UserAssignTraceStation(`UserAssignTraceStationName`,`UserAssignTraceStationOption`,`UsersId`,`TraceStationId`)
VALUES('Products',1,1,1)";

$sql[38] = "CREATE TABLE Lang (
    LangId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    LangName VARCHAR(30) NOT NULL UNIQUE,
    LangVi VARCHAR(200),
    LangEn VARCHAR(200),
    LangCn VARCHAR(200),
    LangKr VARCHAR(200),
    LangOption INT(1),
    LangCreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    LangUpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
    ";

$sql[39] = "INSERT INTO Lang(`LangName`,`LangVi`,`LangEn`,`LangCn`,`LangKr`,`LangOption`)
VALUES('Products','Products','Products','Products','Products',1)";

for ($i=0; $i < count($sql) ; $i++) { 
    $oDB -> query($sql[$i]);
}

$oDB = null;