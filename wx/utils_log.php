<?php
    
function log_base_path()
{
    $ret = dirname(__FILE__) . "../log/";
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

?>
