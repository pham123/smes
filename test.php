<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>In Thử Tem</title>
</head>
<body>
<p>FI</p>
<?php
    for ($i=42; $i < 82; $i++) { 
        # code...
    
?>
    <div style="width:10%; float:left;">
        <p><img src='http://localhost/hev/qr/?data=ACQ30002201_1217_<?php echo sprintf('%05d', $i) ?>' alt=''></p>
        <p>1217_<?php echo sprintf('%05d', $i) ?></p>
    </div>
<?php
    }
?>
</body>
</html>