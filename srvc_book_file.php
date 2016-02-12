<?php
    
require_once "srvc_book_common.php";

function __impl_book_file_base_dir()
{
    return dirname(__FILE__) . "/../reservation/";
}

function __impl_book_file_rticket_path($rticket)
{
    $dir = __impl_book_file_base_dir() . "/" . $rticket->visit_date;
    
    if (!is_dir($dir))
    {
        mkdir($dir);
    }
    
    $path = $dir . "/" . $rticket->visit_mins_slot . ".json";
    return $path;
}

define("KEY_FILE_JSON_RTICKET_COUNT",           "COUNT");
define("KEY_FILE_JSON_RTICKET_LIST",            "ALL");
function impl_book_do_reserve($rticket, $max_per_slot=10)
{
    $path = __impl_book_file_rticket_path($rticket);
    
    // read json from file
    if (file_exists($path))
    {
        $orig_json_str = file_get_contents($path);
    }
    else
    {
        $orig_json_str = '{ ' . KEY_FILE_JSON_RTICKET_COUNT . ' : 0, ' . KEY_FILE_JSON_RTICKET_LIST . ' : {} }';
    }
    
    $json = json_decode($orig_json_str, true);
    
    // check
    if ($json == null ||
        !array_key_exists(KEY_FILE_JSON_RTICKET_COUNT, $json) ||
        !array_key_exists(KEY_FILE_JSON_RTICKET_LIST, $json))
    {
        return BOOK_CODE_ERR_CORRUPT;
    }
    
    // check if full
    $count_rtickets = $json[KEY_FILE_JSON_RTICKET_COUNT];
    if ($count_rtickets + $rticket->num > $max_per_slot)
    {
        return BOOK_CODE_ERR_FULL;
    }
    
    // update json count & list
    $arr_rtickets = $json[KEY_FILE_JSON_RTICKET_LIST];
    if (!is_array($arr_rtickets))
    {
        return BOOK_CODE_ERR_CORRUPT;
    }
    
    array_push($arr_rtickets, $rticket->to_array());
    $json[KEY_FILE_JSON_RTICKET_COUNT] += $rticket->num;
    $json[KEY_FILE_JSON_RTICKET_LIST] = $arr_rtickets;
    $new_json_str = json_encode($json);
    
    // flush
    $handle_f = fopen($path, "w") or die ("ERROR to open $path!");
    fwrite($handle_f, $new_json_str);
    fclose($handle_f);
    
    return BOOK_CODE_OK;
}
    
?>