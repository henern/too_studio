<?php
    
define("SEC_PER_DAY", 24 * 60 * 60);
define("SEC_PER_HOUR", 60 * 60);
define("MIN_PER_HOUR", 60);

function minutes_to_clock_str($mins)
{
    $h = floor($mins / 60);
    $m = $mins % 60;
    
    return str_pad($h, 2, "0", STR_PAD_LEFT) . ":" . str_pad($m, 2, "0", STR_PAD_LEFT);
}

function locale_day_of_week($ts=null)
{
    if ($ts == null)
    {
        $ts = time();
    }
    
    $indx = date("N", $ts);
    
    if ($indx > 0 && $indx < 8)
    {
        $days_of_week = array("星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期天");
        return $days_of_week[$indx - 1];
    }
    
    return "";
}
    
function full_date($ts=null)
{
    if ($ts == null)
    {
        $ts = time();
    }
    
    return date("Y-m-d ", $ts) . locale_day_of_week($ts);
}

?>
