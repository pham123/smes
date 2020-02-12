<?php
define('ROOT_DIR', dirname(__FILE__));
function getfunc($func_name)
{
    include ROOT_DIR.'/function/'.$func_name.'.php';
}
//test
?>