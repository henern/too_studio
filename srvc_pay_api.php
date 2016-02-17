<?php
/*
请确保您的libcurl版本是否支持双向认证，版本高于7.20.1
*/
require_once 'wx_dev.php';

class PayInfo
{
    // 商品或支付单简要描述
    var $body;
    // 附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
    var $attach;
    // 接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
    var $notify_url;
    // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。
    var $openid;
    // 订单总金额，单位为分，
    var $total_fee;
    
    // 微信分配的公众账号ID（企业号corpid即为此appId）
    function app_id()
    {
        return TOO_WX_APPID;
    }
    
    // 微信支付分配的商户号
    function mch_id()
    {
        return TOO_WX_MCH_ID;
    }
    
    // APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
    function spbill_create_ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    
    // 商户系统内部的订单号,32个字符内、可包含字母
    function out_trade_no()
    {
        $clock_now = time();
        return "TOOWX" . date("Ymd", $clock_now) . "$clock_now";
    }
    
    // 随机字符串，不长于32位。
    function nonce_str()
    {
        return md5($this->out_trade_no());
    }
    
    // 取值如下：JSAPI，NATIVE，APP，
    function trade_type()
    {
        return "JSAPI";
    }
    
    function pay_api_sign_key()
    {
        return TOO_WX_PAY_API_SIGN_KEY;
    }
    
    function to_array()
    {
        $ret = array("app_id"           => $this->app_id(),
                     "mch_id"           => $this->mch_id(),
                     "body"             => $this->body,
                     "attach"           => $this->$attach,
                     "notify_url"       => $this->notify_url,
                     "openid"           => $this->openid,
                     "out_trade_no"     => $this->out_trade_no(),
                     "spbill_create_ip" => $this->spbill_create_ip(),
                     "total_fee"        => $this->total_fee,
                     "nonce_str"        => $this->nonce_str(),
                     "trade_type"       => $this->trade_type());
        
        return ksort($ret);
    }
    
    function to_string()
    {
        $plain = null;
        foreach ($this->to_array() as $key ==> $val)
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
    
    function sign()
    {
        $plain = $this->to_string() . "&key=" . $this->pay_api_sign_key();
        return strtoupper(md5($plain));
    }
    
    function to_xml_str()
    {
        $xml = "<xml>";
        foreach ($this->to_array() as $key ==> $val)
        {
            $xml .= "<$key>$val</$key>";
        }
        
        $xml .= "</xml>"
        return $xml;
    }
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

?>

