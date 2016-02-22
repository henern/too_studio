<?php

require_once "utils_log.php";
require_once "utils_time.php";
require_once "utils_array.php";
require_once 'utils_file.php';
    
function array_to_string($arr)
{
    $ret = "";
    $keys = array_keys($arr);

    foreach ($keys as $k)
    {
        $val = $arr[$k];
        $ret .= "$k = $val; ";
    }

    return $ret;
}

function utils_init()
{
    date_default_timezone_set("Asia/Shanghai");
    log_error(true, true);
}

?>
