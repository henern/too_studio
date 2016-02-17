<?php
    
require_once '../srvc_pay_api.php';

utils_init();
    
$err = null;
$ret = __curl_post_ssl("https://api.mch.weixin.qq.com/pay/unifiedorder", 
                       "appid=wxcd488b0381366e82",
                       $err);
var_dump($ret);

?>