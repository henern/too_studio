<?php
    
require_once '../wx_dev.php';
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

echo "<br>parameters</br>";    
var_dump($_GET);
	
$ret = srvc_pay_api_order("JSAPI支付测试", 
                          2, 
                          "",   // ???
                          "支付测试",
                          TOO_HOME_URL . "/wx/pay/srvc_pay_api_notify_test.php");
echo "</br>$ret";

?>