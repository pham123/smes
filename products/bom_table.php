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
            echo "<td>".${'value'.$level}['ProductsNumber']."</td>";
            echo "<td>".${'value'.$level}['ProductsName']."</td>";
            echo "<td>".${'value'.$level}['ProductsSize']."</td>";
            echo "<td>".${'value'.$level}['ProductsNet']."</td>";
            echo "<td>".${'value'.$level}['ProductsGloss']."</td>";
            echo "<td>".${'value'.$level}['ProductsMaterial']."</td>";
            echo "<td>".${'value'.$level}['ProductsUnit']."</td>";
            echo "<td>".${'value'.$level}['BomsQty']."</td>";
            echo "<td>".${'value'.$level}['ProcessesName']."</td>";
            echo "<td>".${'value'.$level}['MakersName']."</td>";
            echo "<td>".${'value'.$level}['ClassifiedMaterialsName']."</td>";
            echo "<td>".${'value'.$level}['MachinesName']."</td>";
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
echo "<th rowspan='2' style='min-width: 130px;'>Part Name</th>";
echo "<th rowspan='2' style='min-width: 100px;'>Size<br>H*W*L</th>";
echo "<th rowspan='2' style='min-width: 60px;'>Net (Kg)</th>";
echo "<th rowspan='2' style='min-width: 60px;'>Gloss (Kg)</th>";
echo "<th rowspan='2' style='min-width:100px;'>Material</th>";
echo "<th rowspan='2' style='min-width: 70px'>Unit</th>";
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
echo "<td colspan='".getMaxLevel($table_data)."'><select name='BomsParentId' required>";

echo "<option value=''>parent PART</option>";
foreach ($table_data as $key => $value) {
    echo "<option value='".$value['BomsId']."'>".$value['ProductsNumber']."</option>";
}
echo "</select></td>";
echo "<td colspan='7'><select name='ProductsId' class='w-75'>";
$products_all = $oDB->sl_all('products',1);
echo "<option value='0'>select product</option>";
foreach ($products_all as $key => $value) {
    echo "<option value='".$value['ProductsId']."'>".$value['ProductsNumber']."-".$value['ProductsName']."</option>";
}
echo "</select></td>";
echo "<td><input type='hidden' value='".$_GET['id']."' name='BomlistsId' /><input style='max-width: 70px;' type='number' name='BomsQty' /></td>";
echo "<td><select name='ProcessesId' style='max-width: 120px'>";
$processes_all = $oDB->sl_all('processes',1);
echo "<option value=''>select process</option>";
foreach ($processes_all as $key => $value) {
    echo "<option value='".$value['ProcessesId']."'>".$value['ProcessesName']."</option>";
}
echo "</select></td>";
echo "<td><select name='MakersId' style='max-width: 110px'>";
$makers_all = $oDB->sl_all('makers',1);
echo "<option value=''>select maker</option>";
foreach ($makers_all as $key => $value) {
    echo "<option value='".$value['MakersId']."'>".$value['MakersName']."</option>";
}
echo "</select></td>";
echo "<td><select name='ClassifiedMaterialsId' style='max-width: 120px'>";
$classifiedmaterials_all = $oDB->sl_all('classifiedmaterials',1);
echo "<option value=''>select classified material</option>";
foreach ($classifiedmaterials_all as $key => $value) {
    echo "<option value='".$value['ClassifiedMaterialsId']."'>".$value['ClassifiedMaterialsName']."</option>";
}
echo "</select></td>";
echo "<td><select name='MachinesId' style='max-width: 60px'>";
$machine_all = $oDB->sl_all('machines',1);
echo "<option value=''>select machine</option>";
foreach ($machine_all as $key => $value) {
    echo "<option value='".$value['MachinesId']."'>".$value['MachinesName']."</option>";
}
echo "</select></td>";
echo "</form></tr>";
generateRow($table_data,0,0);
echo "</tbody>";
echo "</table>";
?>
