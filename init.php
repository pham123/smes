<?php
define('ROOT_DIR', dirname(__FILE__));
function import_function($func_name)
{
    include ROOT_DIR.'/function/'.$func_name.'.php';
}
?>