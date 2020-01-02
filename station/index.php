<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
i_func('db');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="refresh" content="5"> -->
    <link rel="icon" type="image/png" href="../img/halla.png" />
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
            <th style='font-size:30px;'>STATION</th>
            <th>
            <span style='text-align:center;font-size:25px;'>Time : <?php echo date("d-m-Y") ?> </span>
            <span id="txt" style='text-align:center;font-size:25px;'></span>
            </th>
        </tr>
        <tr><td colspan='2' style='text-align:left;'>
            <a href="dc.php" target="_blank"><h1>DIE-CASTING</h1></a>
        </td></tr>
        <tr><td colspan='2' style='text-align:left;'>
            <a href="nc.php" target="_blank"><h1>NC</h1></a>
        </td></tr>
        <tr><td colspan='2' style='text-align:left;'>
            <a href="st1.php" target="_blank"><h1>ST-IN</h1></a>
        </td></tr>
        <tr><td colspan='2' style='text-align:left;'>
            <a href="st.php" target="_blank"><h1>ST</h1></a>
        </td></tr>
        <tr><td colspan='2' style='text-align:left;'>
            <a href="fi.php" target="_blank"><h1>FINAL INSPECTION</h1></a>
        </td></tr>
    </table>
    </form>
</body>
</html>