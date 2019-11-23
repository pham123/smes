<?php

function nav_item($header,$arr){
$a = "
        <hr class='sidebar-divider'>
        <div class='sidebar-heading' style='color:white;'>
        ".$header."
        </div>
";
$b = '';
foreach ($arr as $key => $value) {
    $b = $b."
        <li class='nav-item'>
            <a class='nav-link' href='".$value[0]."' style='Padding:5px 16px;color:white;'>
            <i class='fas fa-fw ".$value[1]."' style='color:white;'></i>
            <span>".$value[2]."</span></a>
        </li>
    ";
}
return $a.$b;
}


// $arr = array(
//     array('#','Item 1'),
//     array('#','Item 2'),
//     array('#','Item 3'),
//     array('#','Item 4')
// );

function nav_item_collapsed($header,$icon,$arr,$id){
    $a = "
        <li class='nav-item'>
            <a class='nav-link collapsed' href='#' data-toggle='collapse' data-target='#".$id."' aria-expanded='true' aria-controls='collapseTwo'>
            <i class='fas fa-fw ".$icon."' style='color:white;'></i>
            <span style='color:white;'>".$header."</span>
            </a>

            <div id='".$id."' class='collapse' aria-labelledby='headingTwo' data-parent='#accordionSidebar'>
            <div class='bg-white py-2 collapse-inner rounded'>
    ";
    $b = '';
    foreach ($arr as $key => $value) {
        $b = $b."
            <a class='collapse-item' href='".$value[0]."'>".$value[1]."</a>
        ";
    }

    $c = "    
            </div>
            </div>
        </li>";
    return $a.$b.$c;
    }

function nav_item_one($link,$icon,$text){
    $a = "      
    <li class='nav-item'>
        <a class='nav-link' href='".$link."'>
        <i class='fas fa-fw ".$icon."'></i>
        <span>".$text."</span></a>
    </li>";
    return $a;
}


