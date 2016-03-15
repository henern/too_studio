<?php
    
require_once 'wx_dev.php';
utils_init();
log_visitor_info();

function __verify_xml($xml_arr)
{
    $sign_from_wx = $xml_arr["sign"];
    unset($xml_arr["sign"]);
    
    $sign = wx_sign_array($xml_arr, TOO_WX_PAY_API_SIGN_KEY, true);
    if ($sign != "" && $sign == $sign_from_wx)
    {
        return true;
    }
    
    return false;
}

define("REDIS_DB_INDEX_SRVC_PAY_API",       117);
function __archive_pay_notification($xml_str, $key)
{
    $redis = new Redis();
    $redis->connect('127.0.0.1');
    $redis->select(REDIS_DB_INDEX_SRVC_PAY_API);
    
    if ($redis->get($key) == FALSE)
    {
        $redis->set($key, $xml_str);
        $redis->save();
    }
    
    $redis->close();
}

$post_raw = $GLOBALS["HTTP_RAW_POST_DATA"];

$xml = (array)simplexml_load_string($post_raw, null, LIBXML_NOCDATA);
log_pay_info(array_to_string($xml) . "\n" . array_to_string($_GET));

$xml_str = array_to_string($xml);
if (__verify_xml($xml))
{
    $ttoken = $xml["out_trade_no"];
    __archive_pay_notification($xml_str, $ttoken);
}

echo "<xml>
      <return_code><![CDATA[SUCCESS]]></return_code>
      <return_msg><![CDATA[OK]]></return_msg>
      </xml>";
    
?>