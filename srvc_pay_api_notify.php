<?php
    
require_once 'srvc_pay_api.php';
utils_init();
log_visitor_info();

function __verify_xml_SUCCESS($xml_arr)
{
    // only archive the xml with SUCCESS, check logs for the FAILs
    $isSUCCESS = $xml_arr["result_code"];
    
    $sign_from_wx = $xml_arr["sign"];
    unset($xml_arr["sign"]);
    
    $sign = wx_sign_array($xml_arr, TOO_WX_PAY_API_SIGN_KEY, true);
    if ($sign != "" && $sign == $sign_from_wx && $isSUCCESS == "SUCCESS")
    {
        return true;
    }
    
    return false;
}

function __pay_notify_by_email($ttoken, $xml_json)
{
    $subject = "[Too塗支付单] " 。 $ttoken;
    
    email_send_to_many(array(TOO_WX_MAIL_ADMIN_Y, TOO_WX_MAIL_ADMIN_W),
                       $subject,
                       $xml_json,
                       notify_email(TOO_HOST_URL));
}

$post_raw = $GLOBALS["HTTP_RAW_POST_DATA"];

$xml = (array)simplexml_load_string($post_raw, null, LIBXML_NOCDATA);
log_pay_info(array_to_string($xml) . "\n" . array_to_string($_GET));

$xml_json = json_encode($xml);
if (__verify_xml_SUCCESS($xml))
{
    $ttoken = $xml["out_trade_no"];
    impl_srvc_pay_api_archive_notification($xml_json, $ttoken);
    
    __pay_notify_by_email($ttoken， $xml_json);
}

echo "<xml>
      <return_code><![CDATA[SUCCESS]]></return_code>
      <return_msg><![CDATA[OK]]></return_msg>
      </xml>";
    
?>