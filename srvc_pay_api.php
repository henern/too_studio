<?php
/*
请确保您的libcurl版本是否支持双向认证，版本高于7.20.1
*/
require_once 'wx_dev.php';

function __curl_post_ssl($url, $vars, &$error, $second = 30, $headers = array())
{
	$ch = curl_init();
	
    //超时时间
	curl_setopt($ch, CURLOPT_TIMEOUT, $second);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
    //这里设置代理，如果有的话
	//curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
	//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	
	//第一种方法，cert 与 key 分别属于两个.pem文件
	curl_setopt($ch, CURLOPT_SSLCERTTYPE, WX_SSL_CERT_TYPE);
	curl_setopt($ch, CURLOPT_SSLCERT, getcwd() . '../' . WX_PATH_API_PEM_CERT);
	curl_setopt($ch, CURLOPT_SSLKEYTYPE, WX_SSL_CERT_TYPE);
	curl_setopt($ch, CURLOPT_SSLKEY, getcwd() . '../' . WX_PATH_API_PEM_KEY);
 
    if (count($headers) >= 1)
    {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
    
    $data = curl_exec($ch);
	
    if ($data)
    {
        curl_close($ch);
        return $data;
	}

    $error = curl_errno($ch);
    curl_close($ch);
    return null;
}

?>

