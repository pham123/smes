<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
i_func('db');

$stationid = 3;
$prestation = 1;
//MCK71113301-1211-17-01
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="refresh" content="5"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trạm NC</title>

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
            <th style='font-size:30px;' >NC</th>
        </tr>
        <tr><td colspan='3' style='text-align:center;'>
        <?php 
        if (isset($_POST['code'])&&$_POST['code']!='') {
            $oDB = new db();
            $code = strtoupper($_POST['code']);
            # Kiểm tra xem tem này đã được khai báo tại công đoạn này chưa
            $LabelHistory = $oDB->query('SELECT * FROM LabelHistory WHERE TraceStationId = ? AND LabelHistoryLabelValue =? ', $stationid,$code)->fetchArray();
            //var_dump();
            if (isset($LabelHistory['TraceStationId'])) {
                $_SESSION['message'] = "<h1 style='background-color:red;'>Mã tem ".$LabelHistory['LabelHistoryLabelValue']." đã được khai báo trên hệ thống : ".$LabelHistory['LabelHistoryCreateDate']." </h1>";
                header('Location:?');
                exit();
            }else{
                // Chưa có thì tiếp tục
            }
            # kiểm tra code có tồn tại trong hệ thống chưa?
            $label = $oDB->query('SELECT * FROM LabelList WHERE LabelListValue = ?', $code)->fetchArray();
            if (isset($label['LabelListId'])) {
                $_SESSION['ProductsId'] = $label['ProductsId'];
                i_func('station');
                $LabelPattern = checkpattern($stationid,$label['ProductsId'],$code);
                //Lấy về thông tin 
                $Products = $oDB->query('SELECT * FROM Products WHERE ProductsId =? ', $label['ProductsId'])->fetchArray();
                //Kiểm tra lại mẫu tem xem có phù hợp không
                $_SESSION['message'] = "Xác nhận số lượng cho mã tem :".$code;
                echo "<span style='width:10%'>Số lượng: <span><input type='text' name='rcode' id='' value='".$code."' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' readonly>";
                echo "<br>";
                echo "<span style='width:10%'>Số lượng đầu vào: <span><input type='number' name='stqty' id='' value='".$LabelPattern['LabelPatternPackingStandard']."' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' readonly>";
                echo "<br>";
                echo "<span style='width:10%'>Số lượng: <span><input type='number' name='quantity' id='' value='' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' min='1' max='".$LabelPattern['LabelPatternPackingStandard']."' autofocus required>";
                echo "<br>";
                echo "<span style='width:10%'>Xác nhận: <input type='text' name='no' id='' value='' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;'  required placeholder='Đọc lại mã tem 1 lần nữa'>";
                echo "<br>";
                echo "<input type='submit' value='submit'>";
            } else {
                $_SESSION['message'] = "<h1 style='background-color:red;'>Mã tem ".$code." không được in ra từ hệ thống hợp lệ </h1>";
                header('Location:?');
                exit();
            }
        } elseif(isset($_POST['rcode'])&&$_POST['rcode']!=''){
            //var_dump($_POST);
            $oDB = new db();
            $rcode = strtoupper($_POST['rcode']);
            $quantity = $_POST['quantity'];
            $stqty = $_POST['stqty'];
           // $quantitymax = $_POST['quantitymax'];
            $no = $_POST['no'];
            //Kiểm tra code đã được đọc ở công đoạn trước chưa, nếu chưa đưa thông báo lỗi rồi đẩy lại
            $sql = "select * from LabelHistory where LabelHistoryLabelValue = ? AND TraceStationId = ?";
            $prehistory = $oDB->query($sql, $no,$prestation)->fetchArray();

           // var_dump($prehistory)
            if (!isset($prehistory['LabelHistoryId'])) {
                $_SESSION['message'] = "<h1 style='background-color:red;'>Mã tem ".$no." chưa được đọc tại công đoạn trước</h1>";
                header('Location:?');
                exit();
            }
            //Kiem tra so luong OK ko vuot qua so luong Ok cua cong doan truoc
            
            $stqty = $prehistory['LabelHistoryQuantityOk'];
            if($_POST['rcode']==$no){
                //kiểm tra số lượng với số lượng tiêu chuẩn
                if ($quantity >= 0 && $quantity <= $stqty ) {
                    $ngqty = $stqty-$quantity;
                } else {
                    $_SESSION['message'] = "<h1 style='background-color:red;'>số lượng nhập vào có vấn đề : ".$quantity." , Số lượng từ công đoạn trước là ".$stqty.", số lượng nhập vào phải lớn hơn hoặc bằng 0 và nhỏ hơn hoặc bằng số lượng nhập vào.</h1>";
                    header('Location:?');
                    exit();
                }
                $oDB->query("INSERT INTO LabelHistory (`TraceStationId`,`LabelHistoryQuantityOk`,`LabelHistoryQuantityNg`,`LabelHistoryLabelValue`,`ProductsId`) VALUES (?,?,?,?,?)",$stationid,$quantity,$ngqty,$rcode,$_SESSION['ProductsId']);
                $_SESSION['message'] = "<h1 style='background-color:green;'>Thêm thành công mã tem ".$rcode." số lượng ".$quantity."</h1>";
                if (isset($_SESSION['Uploadlist'])) {
                    $key = count($_SESSION['Uploadlist']);
                    if ($key>20) {
                        array_shift($_SESSION['Uploadlist']);
                    }
                    $_SESSION['Uploadlist'][$key]['value']=$rcode;
                    $_SESSION['Uploadlist'][$key]['qty']=$quantity;
                    $_SESSION['Uploadlist'][$key]['mother']=$rcode;
                } else {
                    $_SESSION['Uploadlist'][0]['value']=$rcode;
                    $_SESSION['Uploadlist'][0]['qty']=$quantity;
                    $_SESSION['Uploadlist'][$key]['mother']=$rcode;
                }
                header('Location:?');
            }else{
                $_SESSION['message'] = "<h1 style='background-color:red;'>Không thành công, bạn vừa nhập sai mã vào ô xác nhận, mã tại ô xác nhận phải trùng với mã ban đầu.</h1>";
                header('Location:?');
            }

        } else {
            //$_SESSION['message'] = "Đọc mã tem";
            echo "<input type='text' name='code' id='' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' autofocus placeholder='Nhập mã tem'>";
        }
        ?>
        </td></tr>
        <tr>
            <td colspan='3'><?php echo $mesage  = (isset($_SESSION['message'])) ? $_SESSION['message'] : '...' ;?></td>
        </tr>

    </table>
    </form>
    <?php
        if (isset($_SESSION['Uploadlist'])) {
            $newarray = array_reverse($_SESSION['Uploadlist'], true);
            echo "<table style=''>";
            echo "<tr><th>Code</th><th>QTy</th><th>Parent Code</th><th>Status</th></tr>";
            foreach ($newarray as $key => $value) {
                echo "<tr><td>".$value['value']."</td><td>".$value['qty']."</td><td>".$value['mother']."</td><td Style='background-color:green;'>Ok</td></tr>";
            }
            echo "</table>";
        } 
    ?>
</body>
<script>
</script>
</html>