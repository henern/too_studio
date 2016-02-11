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

function visitor_info($extra_arr)
{
    $remote_ip = $_SERVER['REMOTE_ADDR'];
    $get_params = "[ " . array_to_string($_GET) . " ]";
    $remote_ua = $_SERVER['HTTP_USER_AGENT'];
    $extra_str = "[ " . array_to_string($extra_arr) . " ]";
    $time_str = date("Y-m-d H:i:s");

    $ret = "$time_str;  \n" .
           "$remote_ip;  \n" . 
           "$remote_ua;  \n" . 
           "$get_params; \n" .
           "$extra_str;  \n";
    
    return $ret;
}

function utils_init()
{
    date_default_timezone_set("Asia/Shanghai");
    log_error(true, true);
}

?>
