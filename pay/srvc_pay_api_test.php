<?php
    
require_once '../srvc_pay_api.php';

utils_init();
    
$err = null;
$vars = "
    <xml>
    <appid>wx2421b1c4370ec43b</appid>
    <attach>支付测试</attach>
    <body>JSAPI支付测试</body>
    <mch_id>10000100</mch_id>
    <nonce_str>1add1a30ac87aa2db72f57a2375d8fec</nonce_str>
    <notify_url>http://wxpay.weixin.qq.com/pub_v2/pay/notify.v2.php</notify_url>
    <openid>oUpF8uMuAJO_M2pxb1Q9zNjWeS6o</openid>
    <out_trade_no>1415659990</out_trade_no>
    <spbill_create_ip>14.23.150.211</spbill_create_ip>
    <total_fee>1</total_fee>
    <trade_type>JSAPI</trade_type>
    <sign>0CB01533B8C1EF103065174F50BCA001</sign>
    </xml>";
    
$pay_inf = new PayInfo();
$pay_inf->attach = "支付测试";
$pay_inf->body = "JSAPI支付测试";
$pay_inf->total_fee = 2;
$pay_inf->openid = "oUpF8uMuAJO_M2pxb1Q9zNjWeS6o";  // ???
$pay_inf->notify_url = "http://120.25.202.38/wx/pay/srvc_pay_api_notify_test.php";
$var = $pay_inf->to_xml_str();

echo "request xml\n";
var_dump($var);

$ret = __curl_post_ssl("https://api.mch.weixin.qq.com/pay/unifiedorder", 
                       $vars,
                       $err);
echo "\nresponse xml\n";
var_dump($ret);

?>