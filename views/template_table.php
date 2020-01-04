<?php
$tablearr = explode(',',$table_header);
echo "<table class='table table-bordered table-sm' id='dataTable' width='100%' cellspacing='0'>";
echo "<thead>";
echo "<tr>";
foreach ($tablearr as $key => $value) {
    echo "<th>".$oDB->lang($value)."</th>";
}
if(isset($product_picture)){
    echo "<th>Product Picture</th>";
}
echo "</tr>";
echo "</thead>";
// echo "<tfoot>";
// echo  "<tr>";
// foreach ($tablearr as $key => $value) {
//     echo "<th>".$lang[$value]."</th>";
// }
// echo  "</tr>";
// echo "</tfoot>";
echo "<tbody>";
foreach ($table_data as $key => $value) {
    echo "<tr>";
    foreach ($tablearr as $key2 => $value2) {
        if ($key2==0&&isset($table_link)) {
                echo "<td><a href='".$table_link.$value['id']."'>".$value[$value2]."</a></td>";
        }else{
            echo "<td>".$value[$value2]."</td>";
        }
       
    }
    if(isset($product_picture)){
        if(file_exists('./image/small/img_'.$value['id'].'.jpg')) {
            echo "<td><img style='max-height: 30px' src='./image/small/img_".$value['id'].".jpg'></td>";
        } else {
            echo "<td><small>No Picture</small></td>";
        }
    }
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
?>
