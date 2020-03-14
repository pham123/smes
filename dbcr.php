<?php
include 'config.php';
getfunc('db');
getfunc('logs');
getfunc('createdb');
$crdb = new crdb();
//var_dump($_POST);

if (isset($_POST['table'])) {
    # code...
    $crdb->set($_POST['table'])->create();
}

if (isset($_POST['update'])) {
    $crdb->insert($_POST['update']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DATABASE</title>
</head>
<body>
<div style='width:30%;float:left;'>
    <form action="" method="post">
        <p>Tạo 1 table mới ở đây</p>
        <input type="text" name="table" id="" required autofocus>
        <input type="submit" value="Create Table">
    </form>
<br>
    <form action="" method="post">
    <p>Các lệnh update table ở đây</p>
    <textarea name="update" id="" style='width:100%' rows="10"></textarea>
        <input type="submit" value="Update">
    </form>
</div>
<div style="width:68%;float:left;">
<?php
$sql = "SELECT table_name FROM information_schema.tables
WHERE table_schema = 'smes';";
$rs = $crdb->query($sql)->fetchAll();
// var_dump($rs);
$arr = array();
foreach ($rs as $key => $value) {
    $arr[]=$value['TABLE_NAME'];
}

echo implode(', ',$arr);
?>
</div>
<div style='width:100%;float:left;'>
<p>Lịch sử</p>
<p style='font-size:10px;'>
<?php
$name = date("Ymd");
$myfile = fopen("db".$name.".txt", "r") or die("Unable to open file!");
while (!feof($myfile)) {
    $line = fgets($myfile);
    echo "<br>";
    echo $line;
}
fclose($myfile);
?>
</p>
</div>
<div>

</div>
<p>

</p>

</body>
</html>