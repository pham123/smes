<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../config.php');
i_func('db');

$stationid = 4;
//MCK71113301-1211-17-01




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
            $code = $_POST['code'];

            # Kiểm tra xem tem này đã được khai báo tại công đoạn này chưa
            $labelhistory = $oDB->query('SELECT * FROM labelhistory WHERE TraceStationId = ? AND LabelHistoryLabelValue =? ', $stationid,$code)->fetchArray();
            //var_dump();
            if (isset($labelhistory['TraceStationId'])) {
                $_SESSION['message'] = "<h1 style='background-color:red;'>Mã tem ".$labelhistory['LabelHistoryLabelValue']." đã được khai báo trên hệ thống : ".$labelhistory['LabelHistoryCreateDate']." </h1>";
                header('Location:st.php');
                exit();
            }else{
                // Chưa có thì tiếp tục
            }

            # kiem tra mã sản phẩm
            $productsnumber = substr($code, 0, 11);
            $product = $oDB->query('SELECT * FROM products WHERE ProductsNumber = ?', $productsnumber)->fetchArray();

            if (isset($product['ProductsId'])) {
                # kiểm tra thông tin tem có hợp lệ hay không
                $labelpattern = $oDB->query('SELECT * FROM labelpattern WHERE TraceStationId = ? AND ProductsId =? ', $stationid,$product['ProductsId'])->fetchArray();
                //var_dump($labelpattern);
                $pattern = $labelpattern['LabelPatternValue'];
                //exit();
                #Kiểm tra xem mã tem có đảm bảo không

                if (strlen($pattern)!=strlen($code)) {
                    $_SESSION['message'] = "<h1 style='background-color:red;'>Độ dài tem ".$code." không hợp lệ .".$pattern."</h1>";
                    header('Location:st.php');
                    exit();
                }

                $check = 1;
                for ($i=0; $i < strlen($code) ; $i++) { 
                    if ( $pattern[$i]!='*') {
                      if ( $pattern[$i]!=$code[$i]) {
                        //echo "</Br>".$code[$i];
                        $check = 0;
                        break;
                      }
                    }
                  }
                
                if ($check == 0) {
                    $_SESSION['message'] = "<h1 style='background-color:red;'>Cấu trúc tem ".$code." không hợp lệ</h1>";
                    header('Location:st.php');
                    exit();
                }
            }
            //exit();
             


            # kiểm tra code có tồn tại trong hệ thống chưa?
            $label = $oDB->query('SELECT * FROM labellist WHERE LabelListValue = ?', $code)->fetchArray();
            #nếu có rồi
            if (isset($label['LabelListId'])) {
                //Lấy về thông tin 
                $labelpattern = $oDB->query('SELECT * FROM labelpattern WHERE TraceStationId = ? AND ProductsId =? ', $stationid,$label['ProductsId'])->fetchArray();
                $products = $oDB->query('SELECT * FROM products WHERE ProductsId =? ', $label['ProductsId'])->fetchArray();
        
                //Kiểm tra lại mẫu tem xem có phù hợp không
                $_SESSION['message'] = "Xác nhận số lượng cho mã tem :".$code;
                echo "<span style='width:10%'>Số lượng: <span><input type='text' name='rcode' id='' value='".$code."' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' readonly>";
                echo "<br>";

                echo "<span style='width:10%'>Số lượng: <span><input type='number' name='quantity' id='' value='".$labelpattern['LabelPatternPackingStandard']."' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' min='1' max='".$labelpattern['LabelPatternPackingStandard']."'>";
                echo "<br>";
                echo "<span style='width:10%'>Xác nhận: <input type='text' name='no' id='' value='' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' autofocus required placeholder='Đọc lại mã tem 1 lần nữa'>";
                echo "<br>";
                echo "<input type='submit' value='submit'>";
        
            } else {
                #nếu mã tem chưa có trong hệ thống thì yêu cầu nhập mã mother
                $_SESSION['message'] = "Nhập mã tem gốc cho tem :".$code;
                echo "<span style='width:10%'>Số lượng: <span><input type='text' name='mcode' id='' value='".$code."' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' readonly>";
                echo "<br>";

                echo "<span style='width:10%'>Số lượng: <span><input type='number' name='quantity' id='' value='".$labelpattern['LabelPatternPackingStandard']."' style='width:80%;padding:5px;margin:5px;font-size:40px;text-align:center;' min='1' max='".$labelpattern['LabelPatternPackingStandard']."'>";
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
            $mcode = $_POST['mcode'];
            $quantity = $_POST['quantity'];
            $mothercode = $_POST['mothercode'];

            #kiểm tra mother code có hợp lệ hay không
            $mothercode = $oDB->query('SELECT * FROM labellist WHERE LabelListValue = ?', $mothercode)->fetchArray();
            if (isset($mothercode['LabelListId'])) {
                # code...
                echo $mothercode['LabelListValue'].' hợp lệ';
            } else {
                # code...
                echo 'không hợp lệ';
            }
            
            #Hai bạn có cùng nằm trong BOM không



            // if($_POST['rcode']==$no){

            //     $oDB->query("INSERT INTO labelhistory (`TraceStationId`,`LabelHistoryQuantityOk`,`LabelHistoryLabelValue`) VALUES (?,?,?)",$stationid,$quantity,$rcode);
            //     $_SESSION['message'] = "<h1 style='background-color:green;'>Thêm thành công mã tem ".$rcode." số lượng ".$quantity."</h1>";
            //     header('Location:st.php');
            // }else{
            //     $_SESSION['message'] = "<h1 style='background-color:red;'>Không thành công, bạn vừa nhập sai mã vào ô xác nhận, mã tại ô xác nhận phải trùng với mã ban đầu.</h1>";
            //     header('Location:st.php');
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


</body>

<script>
// <?php
// if (isset($_POST['code'])&&$_POST['code']!='') {
// ?>
//             $(document).ready(function() {
//             $(window).keydown(function(event){
//                 if(event.keyCode == 13) {
//                 event.preventDefault();
//                 return false;
//                 }
//             });
//             });
// <?php
// }
// ?>
</script>
</html>