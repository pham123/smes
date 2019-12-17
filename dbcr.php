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
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <p>Tạo 1 table mới ở đây</p>
        <input type="text" name="table" id="" required autofocus>
        <input type="submit" value="Create Table">
    </form>
<br>
    <form action="" method="post">
    <p>Các lệnh update table ở đây</p>
    <textarea name="update" id="" cols="30" rows="10"></textarea>
        <input type="submit" value="Update">
    </form>
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

$sql = "SELECT table_name FROM information_schema.tables
WHERE table_schema = 'smes';";
$rs = $crdb->query($sql)->fetchAll();
echo "<pre>";
var_dump($rs);
echo "</pre>";
?>
</p>

</body>
</html>