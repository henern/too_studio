<?php
    
require_once '../srvc_book_api.php';
    
$err = null;
$ret = __curl_post_ssl("https://api.mch.weixin.qq.com/pay/unifiedorder", 
                       "appid=wxcd488b0381366e82",
                       $err);
var_dump($ret);

?>