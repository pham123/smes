<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
i_func('db');

$stationid = 1;
//MCK71113301-1211-17-01


if (isset($_POST['code'])&&$_POST['code']!='') {
    $oDB = new db();
    $code = $_POST['code'];
    # kiểm tra code có tồn tại trong hệ thống chưa?
    $account = $oDB->query('SELECT * FROM labellist WHERE LabelListValue = ?', $code)->fetchArray();
    if (isset($account['LabelListId'])) {
        echo "đã được định danh";
    } else {
        echo "chưa được định danh";
    }
    

} else {
    # code...
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="refresh" content="5"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <script>
        function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('txt').innerHTML =
        h + ":" + m + ":" + s;
        var t = setTimeout(startTime, 500);
        }
        function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
        }
    </script>
    <style>
        table {
        border-collapse: collapse;
        }

        table, th, td {
        border: 1px solid black;
        }
    </style>
</head>
<body onload="startTime()">
    <form action="" method="post">
    <table style='width:100%;body'>
        <tr>
            <th style='font-size:30px;'>QR Code record</th>
            <th>
            <span style='text-align:center;font-size:25px;'>Time : <?php echo date("d-m-Y") ?> </span>
            <span id="txt" style='text-align:center;font-size:25px;'></span>
            </th>
            <th style='font-size:30px;' >DIE CASTING</th>
        </tr>
        <tr><td colspan='3' style='text-align:center;'><input type="text" name="code" id="" style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' autofocus></td></tr>
    </table>
    </form>


</body>
</html>