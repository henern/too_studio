<?php

require_once '../wx_dev.php';

$redirect_uri = TOO_HOME_URL . "/wx/pay/srvc_pay_api_test.php";
$scope = "snsapi_base";   // 'snsapi_userinfo';
$state = "TOO-WX";

$auth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . TOO_WX_APPID .
            '&redirect_uri=' . urlencode($redirect_uri) . 
            '&response_type=code&scope=' . $scope . 
            '&state=' . $state .'#wechat_redirect';
header("Location:" . $auth_url);
    
?>