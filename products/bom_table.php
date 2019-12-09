<?php
function getDirectChild($table_data, $parent_id){
    if(!is_array($table_data) || !is_array($table_data[0]))
    return $table_data;
    $result = [];
    foreach($table_data as $key => $value) {
        if($value['BomsParentId'] == $parent_id) {
            $result[] = $value;
        }
    }
    return $result;
}

//find max-level of bom
function getMaxLevel($table_data){
    $max_level = 1;
    foreach($table_data as $key => $value) {
        $levels = explode('-', $value['BomsPath']);
        if(count($levels) > $max_level){
            $max_level = count($levels);
        }
    }
    return $max_level;
    
}

//số thứ tự bản ghi
$index = 0;
//hàm tạo hàng trong table
function generateRow($data, $level, $parent_id){
    global $index,$table_data;
    if(is_array($data) && count($data) > 0)
    {
        foreach ($rows = getDirectChild($data,$parent_id) as $key => ${'value'.$level}) {
            echo "<tr>";
            echo "<td>".++$index."</td>";
            for($i = 0; $i <getMaxLevel($table_data); $i++){
                if($i==$level){
                    echo "<td>O</td>";
                } else {
                    echo "<td></td>";
                }
            }
            echo "<td>".${'value'.$level}['BomsPartNo']."</td>";
            echo "<td>".${'value'.$level}['BomsPartName']."</td>";
            echo "<td>".${'value'.$level}['BomsSize']."</td>";
            echo "<td>".${'value'.$level}['BomsNet']."</td>";
            echo "<td>".${'value'.$level}['BomsGloss']."</td>";
            echo "<td>".${'value'.$level}['BomsMaterial']."</td>";
            echo "<td>".${'value'.$level}['BomsUnit']."</td>";
            echo "<td>".${'value'.$level}['BomsQty']."</td>";
            echo "<td>".${'value'.$level}['BomsProcess']."</td>";
            echo "<td>".${'value'.$level}['BomsMaker']."</td>";
            echo "<td>".${'value'.$level}['BomsClassifiedMaterial']."</td>";
            echo "<td>".${'value'.$level}['BomsMachine']."</td>";
            echo "</tr>";
            generateRow($data,$level+1, ${'value'.$level}['BomsId']);
        }
    }
}
echo "<table id='bom_table' style='width: 100%; over-flow: none' cellspacing='0'>";
echo "<thead>";
echo "<tr>";
echo "<th rowspan='2'>No.</th>";
echo "<th colspan='".getMaxLevel($table_data)."' style='width: 10%;'>Level</th>";
echo "<th rowspan='2'>Part No.</th>";
echo "<th rowspan='2'>Part Name</th>";
echo "<th rowspan='2'>Size<br>H*W*L</th>";
echo "<th rowspan='2'>Net (Kg)</th>";
echo "<th rowspan='2'>Gloss (Kg)</th>";
echo "<th rowspan='2'>Material</th>";
echo "<th rowspan='2' style='width: 70px !important;'>Unit</th>";
echo "<th rowspan='2'>Q'ty</th>";
echo "<th rowspan='2'>Process</th>";
echo "<th rowspan='2'>Maker</th>";
echo "<th rowspan='2'>Classified material</th>";
echo"<th rowspan='2'>Machine</th>";
echo "</tr>";
echo "<tr>";
for ($i=0; $i <getMaxLevel($table_data) ; $i++) { 
    echo "<th>".$i."</th>";
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
echo "<tr><form action='listen-create-bom.php' method='post'>";
echo "<td><button type='submit' class='btn-secondary'>add</button></td>";
echo "<td colspan='".getMaxLevel($table_data)."'><select name='BomsParentId'>";

$boms_all = $oDB->sl_all('boms',1);
echo "<option value='0'>parent PART</option>";
foreach ($boms_all as $key => $value) {
    echo "<option value='".$value['BomsId']."'>".$value['BomsPartNo']."</option>";
}
echo "</select></td>";
echo "<td><input type='text' style='max-width: 140px;' name='BomsPartNo' /></td>";
echo "<td><input type='text' style='max-width: 180px;'  name='BomsPartName' /></td>";
echo "<td><input type='text' style='max-width: 110px' name='BomsSize' /></td>";
echo "<td><input type='number' style='max-width: 90px;' step='.001' name='BomsNet' /></td>";
echo "<td><input type='number' style='max-width: 90px;' step='.001' name='BomsGloss' /></td>";
echo "<td><input type='text' style='max-width: 120px;' name='BomsMaterial' /></td>";
echo "<td><input type='text' style='max-width: 70px;' name='BomsUnit' /></td>";
echo "<td><input style='max-width: 70px;' type='number' name='BomsQty' /></td>";
echo "<td><input type='text' style='max-width: 120px;' name='BomsProcess' /></td>";
echo "<td><input type='text' style='max-width: 100px;' name='BomsMaker' /></td>";
echo "<td><input type='text' style='max-width: 110px;' name='BomsClassifiedMaterial' /></td>";
echo "<td><input type='text' style='max-width: 100px' name='BomsMachine' /></td>";
echo "</form></tr>";
generateRow($table_data,0,0);
echo "</tbody>";
echo "</table>";
?>
