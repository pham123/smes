<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="refresh" content="5"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trạm Final Inspection</title>
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
            <th style='font-size:30px;' >FINAL INSPECTION NO UNIQUE</th>
        </tr>
        <tr>
            <th style='font-size:30px;'><?php echo $_SESSION['station']['ProductsName'] ?></th>
            <th><?php echo $retVal = (isset($_SESSION['station']['lasttime'])) ? $_SESSION['station']['lasttime'] : 'NA' ;  ?></th>
            <th style='font-size:30px;' ><?php echo $_SESSION['station']['ProductsNumber'] ?></th>
        </tr>

        <tr><td colspan='3' style='text-align:center;'>
        <?php 
        if (isset($_POST['code'])&&$_POST['code']!='') {

            $code = $_POST['code'];
            //Kiểm tra mã tem có hợp lệ không

            #Kiểm tra xem mã tem có đảm bảo không
            $check = 1;
            if (strlen($pattern)!=strlen($code)) {
                $_SESSION['message'] = "<h1 style='background-color:red;'>Độ dài tem ".$code." không hợp lệ .".$pattern."</h1>";
                header('Location: ?');
                exit();
            }
            for ($i=0; $i < strlen($code) ; $i++) { 
                if ( $pattern[$i]!='*') {
                    if ( $pattern[$i]!=$code[$i]) {
                        $_SESSION['message'] = "<h1 style='background-color:red;'>Cấu trúc tem ".$code." không hợp lệ</h1>";
                    $check = 0;
                    break;
                    }
                }else{
                    if ( !is_numeric($code[$i])) {
                        $_SESSION['message'] = "<h1 style='background-color:red;'>Cấu trúc tem ".$code." không hợp lệ, phần chữ số có chứa kí tự</h1>";
                        $check = 0;
                        break;
                        }
                }
            }

            if ($check == 0) {
                header('Location: ?');
                exit();
            }
            
            // $pattern
            $Productsnumber = substr($code, 0, 11);
            $product = $oDB->query('SELECT * FROM Products WHERE ProductsNumber = ?', $Productsnumber)->fetchArray();
            if (isset($product['ProductsId'])) {
                # kiểm tra thông tin tem có hợp lệ hay không
                # Chèn thông tin tem vào list và history
                
                if (isset($_SESSION['station']['lasttime'])) {
                    # code...
                i_func('datedif');
                $date_1 = $_SESSION['station']['lasttime'];
                $date_2 = date('Y-m-d h:i:s');
                $datedif = dateDifference($date_1 , $date_2);

                if ($datedif<6) {
                    $_SESSION['message'] = "<h1 style='background-color:red;'>Tem vừa được đọc lúc ".$date_1.", đợi 5s để đọc lại.</h1>";
                    header('Location: ?');
                    exit();
                }

                }else{
                    $_SESSION['station']['lasttime'] = date('Y-m-d h:i:s');
                }

                //Kiểm tra xem đã có bản ghi trong labelist chưa nếu có thì bỏ qua nếu không thì ghi vào labelist

                // $oDB->query("INSERT INTO LabelList (`ProductsId`,`LabelListValue`,`LabelListMotherId`) VALUES (?,?,?)",$product['ProductsId'],$mcode,$mothercodeinfo['LabelListId']);

                $oDB->query("INSERT INTO LabelHistory (`TraceStationId`,`ProductsId`,`LabelHistoryQuantityOk`,`LabelHistoryLabelValue`) VALUES (?,?,1,?)",$stationid,$product['ProductsId'],$code);


                $_SESSION['message'] = "<h1 style='background-color:blue;'>Thêm thành công ".$code."</h1>";
                $_SESSION['station']['lasttime'] = date('Y-m-d h:i:s');
                $_SESSION['station']['lastcode'] = $code;
                header('Location: ?');
                exit();

                // echo "Thêm thành công";
            }

        } else {

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
        $hientai = date("Y-m-d H:i:s");
        $mocthoigian = date("Y-m-d 20:00:00");
        $yesterday = date ("Y-m-d 20:00:00",strtotime('-1 days'));
        $tomorrow = date ("Y-m-d 20:00:00",strtotime('+1 days'));
        $shiftarray = array();
        if ($hientai<$mocthoigian) {
            $shiftarray[0]['start']= date("Y-m-d 08:00:00");
            $shiftarray[0]['end']= date("Y-m-d 20:00:00");
            $shiftarray[1]['start']= date ("Y-m-d 16:00:00",strtotime('-1 days'));
            $shiftarray[1]['end']= date("Y-m-d 08:00:00");
        }else{
            $shiftarray[0]['start']= date("Y-m-d 20:00:00");
            $shiftarray[0]['end']= date("Y-m-d H:i:s");
            $shiftarray[1]['start']= date("Y-m-d 08:00:00");
            $shiftarray[1]['end']= date("Y-m-d 20:00:00");
        }
        
        # code...

        $sql="SELECT 
        prd.ProductsName,
        prd.ProductsNumber,
        ts.TraceStationName,
        SUM(CASE WHEN lh.LabelHistoryCreateDate BETWEEN '".$shiftarray[0]['start']."' AND '".$shiftarray[0]['end']."' THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS TotalOk1,
        SUM(CASE WHEN lh.LabelHistoryCreateDate BETWEEN '".$shiftarray[1]['start']."' AND '".$shiftarray[1]['end']."' THEN lh.LabelHistoryQuantityOk ELSE 0 END) AS TotalOk2
        FROM LabelHistory lh
        INNER JOIN Products prd ON prd.ProductsId = lh.ProductsId
        INNER JOIN TraceStation ts ON ts.TraceStationId = lh.TraceStationId AND ts.TraceStationId = ?
        WHERE lh.LabelHistoryCreateDate BETWEEN '".$shiftarray[1]['start']."' AND '".$shiftarray[0]['end']."'
        GROUP BY prd.ProductsName, prd.ProductsNumber, ts.TraceStationName";
       $total =  $oDB->query($sql,$stationid)->fetchAll();

       //var_dump($total);
    ?>

    <table>
    <tr>
        <th rowspan='2'>Tên sản phẩm</th>
        <th rowspan='2'>Mã sản phẩm</th>
        <th colspan='3'>CA1</th>
        <th colspan='3'>CA2</th>
    </tr>
    <tr>
        <th><?php echo $shiftarray[1]['start']?></th>
        <th><?php echo $shiftarray[1]['end']?></th>
        <th><?php echo $shiftarray[0]['start']?></th>
        <th><?php echo $shiftarray[0]['end']?></th>
    </tr>
    <tr>
        <th>Kế hoạch</th>
        <th>Kết quả</th>
        <th>Kế hoạch</th>
        <th>Kết quả</th>
    </tr>
    <?php
    foreach ($total as $key => $value) {
        ?>
            <tr>
                <td><?php echo $value['ProductsName'] ?></td>
                <td><?php echo $value['ProductsNumber'] ?></td>
                <td>-</td>
                <td><?php echo $value['TotalOk2'] ?></td>
                <td>-</td>
                <td><?php echo $value['TotalOk1'] ?></td>
            </tr>

        <?php
    }
    
    ?>
    
    </table>
</body>
</html>
