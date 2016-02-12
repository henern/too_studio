<?php
    
function visitor_info($extra_arr)
{
    $remote_ip = $_SERVER['REMOTE_ADDR'];
    $get_params = "[ " . array_to_string($_GET) . " ]";
    $remote_ua = $_SERVER['HTTP_USER_AGENT'];
    $extra_str = "[ " . array_to_string($extra_arr) . " ]";
    $time_str = date("Y-m-d H:i:s");
    $page_url = $_SERVER['SCRIPT_URI'];

    $ret = "$page_url;   \n" .
           "$time_str;   \n" .
           "$remote_ip;  \n" . 
           "$remote_ua;  \n" . 
           "$get_params; \n" .
           "$extra_str;  \n";
    
    return $ret;
}

function log_base_path()
{
    $ret = dirname(__FILE__) . "/../log/";
    return $ret;
}
    
function log_error_path($file_name="")
{
    return log_base_path() . "error/$file_name";
}

function log_visitor_path($file_name="")
{
    return log_base_path() . "visitor/$file_name";
}

function log_visitor_info($extra_arr=array())
{
    $v_info = visitor_info($extra_arr);
    
    $today_str = date("Ymd");
    $log_fname = log_visitor_path("log_vistor_$today_str.txt");
    
    $handle_file = fopen($log_fname, "a") or die("ERROR TO OPEN $log_fname!");
    
    fwrite($handle_file, "[VISITOR-INFO-BEGIN]\n");
    fwrite($handle_file, $v_info);
    fwrite($handle_file, "[VISITOR-INFO-END]\n\n");

    fclose($handle_file);

    return true;
}

function log_error($err2display, $err2file)
{
    error_reporting(E_ALL);

    if ($err2display)
    {
        ini_set('display_errors', '1');
    }

    if ($err2file)
    {
        $today_str = date("Ymd");
        ini_set('error_log', log_error_path("log_err_$today_str.txt"));
    }
}

?>
