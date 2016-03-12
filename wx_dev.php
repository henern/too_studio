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

function wx_sign_array($array_to_sign, $key)
{
    $plain = wx_array_to_string($array_to_sign);
    if ($plain != "")
    {
        $plain = $plain . "&";
    }
    
    $plain = $plain . "key=" . $key;
    return strtoupper(md5($plain));
}

?>
