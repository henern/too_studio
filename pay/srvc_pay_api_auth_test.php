<?php

require_once '../wx_dev.php';

$REDIRECT_URI = "http://120.25.202.38/wx/pay/srvc_pay_api_test.php";
$scope = "snsapi_base";   // 'snsapi_userinfo';
$state = "TOO-WX";

$auth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . TOO_WX_APPID .
            '&redirect_uri=' . urlencode($REDIRECT_URI) . 
            '&response_type=code&scope=' . $scope . 
            '&state=' . $state .'#wechat_redirect';
header("Location:" . $auth_url);
    
?>