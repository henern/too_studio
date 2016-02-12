<?php
    
function array_obj4key($arr, $key)
{
    if ($key != null &&
        is_array($arr) &&
        array_key_exists($key, $arr))
    {
        $val = $arr[$key];
        return $val;
    }
    
    return null;
}

function array_string4key($arr, $key)
{
    $ret = array_obj4key($arr, $key);
    if (is_string($ret))
    {
        return $ret;
    }
    
    return null;
}
    
function array_int4key($arr, $key)
{
    $ret = array_obj4key($arr, $key);
    if (is_int($ret))
    {
        return $ret;
    }
    
    return null;
}

function array_array4key($arr, $key)
{
    $ret = array_obj4key($arr, $key);
    if (is_array($ret))
    {
        return $ret;
    }
    
    return null;
}

function array_number4key($arr, $key)
{
    $ret = array_obj4key($arr, $key);
    if (is_numeric($ret))
    {
        return $ret;
    }
    
    return null;
}

?>