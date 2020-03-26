<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
i_func('db');
$oDB = new db();
$stationid = 6;
$prestation = 5;
if (isset($_POST['prdnumber'])) {
    $rs = $oDB->query('SELECT * FROM Products Where ProductsNumber=?',$_POST['prdnumber'])->fetchArray();
    // var_dump($rs);
    if (isset($rs['ProductsId'])) {
        $_SESSION['station']['id'] = $rs['ProductsId'];
        $_SESSION['station']['ProductsName'] = $rs['ProductsName'];
        $_SESSION['station']['ProductsNumber'] = $rs['ProductsNumber'];
        $_SESSION['message']="<h1 style='background-color:green;'>Đổi thành công sang mã sản phẩm ".$rs['ProductsNumber']." / ".$rs['ProductsName']."</h1>";
    }else{
        unset($_SESSION['station']);
        $_SESSION['message']="<h1 style='background-color:red;'>Không tìm thấy mã sản phẩm ".$_POST['prdnumber']." trong cơ sở dữ liệu, liên hệ quản lý để thiết lập</h1>";
    }
}

if (isset($_SESSION['station']['id'])) {
    if (is_numeric($_SESSION['station']['id'])) {
        $rs = $oDB->query('SELECT * FROM LabelPattern Where ProductsId=? AND TraceStationId =?',$_SESSION['station']['id'],$stationid)->fetchArray();
        // var_dump($rs);
        // $setunique = $rs['LabelPatternUnique'];
        if (isset($rs['LabelPatternUnique'])) {
            $setunique = ($rs['LabelPatternUnique']==Null) ? 1 : $rs['LabelPatternUnique'] ;
            $pattern = $rs['LabelPatternValue'];
        }else{
            unset($_SESSION['station']);
            $_SESSION['message']="<h1 style='background-color:red;'>Mã sản phẩm ".$_POST['prdnumber']." chưa được thiết lập mẫu tem trên hệ thống</h1>";
            
        }
        
    }else{
        //Về trang lựa chọn sản phẩm
        
    }
    $setunique;
    
}else{
    //về trang lựa chọn sản phẩm
    echo "chưa có sản phẩm nào được chọn";
    
}

if (isset($setunique)&&is_numeric($setunique)) {
    
    switch ($setunique) {
        case 1:
            // echo "1";
            include('temp/unique-not-oqc.php');
            break;
        case 2:
            // echo "2";
            include('temp/nounique-oqc.php');
            break;
        case 3:
            // echo "3";
            include('temp/unique-not-oqc.php');
            break;
        default:
            
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nhập vào mã sản phẩm</title>
</head>
<body>
    <h1>Nhập vào mã sản phẩm cần nhập</h1>
    <form action="" method="post">
        <input name='prdnumber' pattern="^[A-Z0-9]{5,15}$" type="text" style='font-size:50px;text-align:center;'>
    </form>
</body>
</html>
<?php
echo $_SESSION['message'];
?>
