<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
i_func('db');

$stationid = 4;
//MCK71113301-1211-17-01
$prestation = 3;




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
            <th style='font-size:30px;' >ST</th>
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

            # kiem tra mã sản phẩm
            $Productsnumber = substr($code, 0, 11);
            $product = $oDB->query('SELECT * FROM Products WHERE ProductsNumber = ?', $Productsnumber)->fetchArray();

            if (isset($product['ProductsId'])) {
                # kiểm tra thông tin tem có hợp lệ hay không
                i_func('station');
                $LabelPattern = checkpattern($stationid,$product['ProductsId'],$code);
            }
             


            # kiểm tra code có tồn tại trong hệ thống chưa?
            $label = $oDB->query('SELECT * FROM LabelList WHERE LabelListValue = ?', $code)->fetchArray();
            #nếu có rồi
            if (isset($label['LabelListId'])) {
                //Lấy về thông tin 
                $LabelPattern = $oDB->query('SELECT * FROM LabelPattern WHERE TraceStationId = ? AND ProductsId =? ', $stationid,$label['ProductsId'])->fetchArray();
                $Products = $oDB->query('SELECT * FROM Products WHERE ProductsId =? ', $label['ProductsId'])->fetchArray();
        
                //Kiểm tra lại mẫu tem xem có phù hợp không
                $_SESSION['message'] = "Xác nhận số lượng cho mã tem :".$code;
                echo "<span style='width:10%'>Số lượng: <span><input type='text' name='rcode' id='' value='".$code."' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' readonly>";
                echo "<br>";

                echo "<span style='width:10%'>Số lượng: <span><input type='number' name='quantity' id='' value='".$LabelPattern['LabelPatternPackingStandard']."' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' min='1' max='".$LabelPattern['LabelPatternPackingStandard']."'>";
                echo "<br>";
                echo "<span style='width:10%'>Xác nhận: <input type='text' name='no' id='' value='' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' autofocus required placeholder='Đọc lại mã tem 1 lần nữa'>";
                echo "<br>";
                echo "<input type='submit' value='submit'>";
        
            } else {
                #nếu mã tem chưa có trong hệ thống thì yêu cầu nhập mã mother
                $_SESSION['message'] = "Nhập mã tem gốc cho tem :".$code;
                echo "<span style='width:10%'>Số lượng: <span><input type='text' name='mcode' id='' value='".$code."' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' readonly>";
                echo "<br>";

                echo "<span style='width:10%'>Số lượng: <span><input type='number' name='quantity' id='' value='".$LabelPattern['LabelPatternPackingStandard']."' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' min='1' max='".$LabelPattern['LabelPatternPackingStandard']."'>";
                echo "<br>";
                echo "<span style='width:10%'>Xác nhận: <input type='text' name='mothercode' id='' value='' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' autofocus required placeholder='Đọc mã tem gốc'>";
                echo "<br>";
                echo "<input type='submit' value='submit'>";


                // $_SESSION['message'] = "<h1 style='background-color:red;'>Mã tem ".$code." không được in ra từ hệ thống hợp lệ </h1>";
                // header('Location:st.php');
                // exit();
            }
            
        
        } elseif(isset($_POST['mcode'])&&$_POST['mcode']!=''){
            //var_dump($_POST);
            $oDB = new db();
            $mcode = strtoupper($_POST['mcode']);
            $quantity = $_POST['quantity'];
            $mothercode = $_POST['mothercode'];

            $Productsnumber = substr($mcode, 0, 11);
            $product = $oDB->query('SELECT * FROM Products WHERE ProductsNumber = ?', $Productsnumber)->fetchArray();

            #kiểm tra mother code có hợp lệ hay không
            $mothercodeinfo = $oDB->query('SELECT * FROM LabelList WHERE LabelListValue = ?', $mothercode)->fetchArray();

            // echo $product['ProductsId'];
            // echo $mothercodeinfo['ProductsId'];

            //exit();





            if (isset($mothercodeinfo['LabelListId'])&&$product['ProductsId']==$mothercodeinfo['ProductsId']) {

                #thêm bước kiểm tra số lượng
                #Lấy về số lượng Ok gần nhất của motherlabel
                $query = $oDB->query('SELECT * FROM LabelHistory WHERE LabelHistoryLabelValue = ? AND TraceStationId = ? ORDER BY LabelHistoryId DESC LIMIT 1', $mothercode,$prestation)->fetchArray();
                if (isset($query['LabelHistoryQuantityOk'])) {
                    $motherquantity = $query['LabelHistoryQuantityOk'];                
                }else{
                    $_SESSION['message'] = "<h1 style='background-color:red;'>Không thành công, tem chưa được đọc ở công đoạn trước </h1>";
                    header('Location:?');
                    exit();
                }

                #lấy về tổng số lượng của các label con

                $sql = "SELECT SUM(lh.LabelHistoryQuantityOk) as total FROM LabelHistory lh
                inner join LabelList lbl on lbl.LabelListValue = lh.LabelHistoryLabelValue
                Where lbl.LabelListMotherId = ?";

                $total = $oDB->query($sql, $mothercodeinfo['LabelListId'])->fetchArray();

                // echo ($total['total']);
                // echo "/";
                // echo ($motherquantity);

                if (isset($total['total'])&&$total['total']==$motherquantity) {
                    # code...
                    $_SESSION['message'] = "<h1 style='background-color:red;'>Không thành công, bạn đã nhập ".$total['total']."/".$motherquantity." </h1>";
                    header('Location:?');
                    exit();
                }

                
                # code...
                $mothercodeinfo['LabelListValue'].' hợp lệ';

                # Chèn thông tin tem vào list và history
                $oDB->query("INSERT INTO LabelList (`ProductsId`,`LabelListValue`,`LabelListMotherId`) VALUES (?,?,?)",$mothercodeinfo['ProductsId'],$mcode,$mothercodeinfo['LabelListId']);
                $oDB->query("INSERT INTO LabelHistory (`TraceStationId`,`LabelHistoryQuantityOk`,`LabelHistoryLabelValue`) VALUES (?,?,?)",$stationid,$quantity,$mcode);


                if (isset($_SESSION['Uploadlist'])) {
                    $key = count($_SESSION['Uploadlist']);
                    if ($key>20) {
                        array_shift($_SESSION['Uploadlist']);
                    }
                    $_SESSION['Uploadlist'][$key]['value']=$mcode;
                    $_SESSION['Uploadlist'][$key]['qty']=$quantity;
                    $_SESSION['Uploadlist'][$key]['mother']=$mothercodeinfo['LabelListValue'];
                } else {
                    $_SESSION['Uploadlist'][0]['value']=$mcode;
                    $_SESSION['Uploadlist'][0]['qty']=$quantity;
                    $_SESSION['Uploadlist'][$key]['mother']=$mothercodeinfo['LabelListValue'];
                }

                $_SESSION['message'] = "<h1 style='background-color:green;'>Thêm thành công .".$mcode."</h1>";
                header('Location:?');
                
                exit();

            } else {
                # code...
                
                $_SESSION['message'] = "<h1 style='background-color:red;'>Đã có lỗi xảy ra </h1>";
                header('Location:?');
                exit();
            }
            
            #Hai bạn có cùng nằm trong BOM không



            // if($_POST['rcode']==$no){

            //     $oDB->query("INSERT INTO LabelHistory (`TraceStationId`,`LabelHistoryQuantityOk`,`LabelHistoryLabelValue`) VALUES (?,?,?)",$stationid,$quantity,$rcode);
            //     $_SESSION['message'] = "<h1 style='background-color:green;'>Thêm thành công mã tem ".$rcode." số lượng ".$quantity."</h1>";
            //     header('Location:?');
            // }else{
            //     $_SESSION['message'] = "<h1 style='background-color:red;'>Không thành công, bạn vừa nhập sai mã vào ô xác nhận, mã tại ô xác nhận phải trùng với mã ban đầu.</h1>";
            //     header('Location:?');
            // }

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