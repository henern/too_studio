<?php

require_once "wx_common.php";
require_once (__DIR__ . "/../wx_private/wx_private.php");
require_once "utils.php";

function too_wx_check_sign()
{
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];	
        		
    $token = TOO_WX_TOKEN;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
	
    if ($tmpStr == $signature)
    {
        return true;
    }

    return false;
}

function too_wx_echo_if_require()
{
    $key_echostr = "echostr";
    if (!array_key_exists($key_echostr, $_GET))
    {
        return false;
    }

    if (!too_wx_check_sign())
    {
        return false;
    }

    $echo_str = $_GET[$key_echostr];
    echo $echo_str;

    return true;
}

function wx_array_to_string($arr)
{
    $plain = null;
    foreach ($arr as $key => $val)
    {
        if ($plain == null)
        {
            $plain = "$key=$val";
        }
        else
        {
            $plain .= "&$key=$val";
        }
    }
    
    return $plain;
}

function wx_sign_array($array_to_sign, $key, $need_sort = false)
{
    if ($need_sort)
    {
        ksort($array_to_sign);
    }
    
    $plain = wx_array_to_string($array_to_sign);
    if ($plain != "")
    {
        $plain = $plain . "&";
    }
    
    $plain = $plain . "key=" . $key;
    return strtoupper(md5($plain));
}

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

function __curl_get_ssl($url, &$error, $second = 10, $headers = array())
{
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $second); 
	
    if (count($headers) >= 1)
    {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	//第一种方法，cert 与 key 分别属于两个.pem文件
	curl_setopt($ch, CURLOPT_SSLCERTTYPE, WX_SSL_CERT_TYPE);
	curl_setopt($ch, CURLOPT_SSLCERT, getcwd() . '../' . WX_PATH_API_PEM_CERT);
	curl_setopt($ch, CURLOPT_SSLKEYTYPE, WX_SSL_CERT_TYPE);
	curl_setopt($ch, CURLOPT_SSLKEY, getcwd() . '../' . WX_PATH_API_PEM_KEY);
	
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
