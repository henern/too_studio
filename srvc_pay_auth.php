<?php
    
require_once 'wx_dev.php';

$query_str = $_SERVER["QUERY_STRING"];
$redirect_uri = TOO_HOME_URL . "/wx/srvc_pay.php?" . $query_str;
$scope = "snsapi_base";   // 'snsapi_userinfo';
$state = "TOO-WX";

$auth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . TOO_WX_APPID .
            '&redirect_uri=' . urlencode($redirect_uri) . 
            '&response_type=code&scope=' . $scope . 
            '&state=' . $state .'#wechat_redirect';
header("Location:" . $auth_url);
    
?>