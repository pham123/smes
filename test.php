<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
    for ($i=1; $i < 10 ; $i++) { 
        # code...
    ?>
    <span>
        <span>ACQ30002201_1212_0000<?php echo $i?></span>
        <img src="http://192.168.1.2:88/qr/index.php?data=ACQ30002201_1212_0000<?php echo $i?>" alt="">
    </span>

    <?php  } ?>

    <br>
    <?php
    for ($i=1; $i < 10 ; $i++) { 
        # code...
    ?>
    <span>
        <span>MCK71113301_1212_0000<?php echo $i?></span>
        <img src="http://192.168.1.2:88/qr/index.php?data=MCK71113301_1212_0000<?php echo $i?>" alt="">
    </span>

    <?php  } ?>

</body>
</html>