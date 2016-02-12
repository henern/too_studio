<?php

require "./utils_log.php";

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
