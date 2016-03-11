<?php
/*
请确保您的libcurl版本是否支持双向认证，版本高于7.20.1
*/
require_once 'wx_dev.php';

define("PAY_API_DEFAULT_NOTIFY_URL",    TOO_HOME_URL . "/wx/srvc_pay_api_notify.php");
define("PAY_API_ORDER_URL",             "https://api.mch.weixin.qq.com/pay/unifiedorder");

class PayInfo
{
    // 商品或支付单简要描述
    var $body;
    // 附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
    var $attach;
    // 接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
    var $notify_url;
    // 订单总金额，单位为分，
    var $total_fee;
    // oauth授权获得的code和openid
    var $code_cached;
    var $openid_cached;
    
    function __construct()
    {
        $this->code_cached = "";
        $this->openid_cached = "";
    }
    
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
    
    // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。
    function openid()
    {
        $oid = "";
        
        if (!array_key_exists("code", $_GET))
        {
            return "";
        }
        
        $code = $_GET["code"]; 
        
        // return the openid in cache
        if ($this->code_cached != "" && 
            $this->code_cached == $code && 
            $this->openid_cached != "" &&
            strlen($this->openid_cached) > 0)
        {
            return $this->openid_cached;
        }
        
        $this->code_cached = $code;
        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . TOO_WX_APPID . 
                         '&secret=' . TOO_WX_APPSECRET . 
                         '&code=' . $code . 
                         '&grant_type=authorization_code';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $get_token_url); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	
		//第一种方法，cert 与 key 分别属于两个.pem文件
		curl_setopt($ch, CURLOPT_SSLCERTTYPE, WX_SSL_CERT_TYPE);
		curl_setopt($ch, CURLOPT_SSLCERT, getcwd() . '../' . WX_PATH_API_PEM_CERT);
		curl_setopt($ch, CURLOPT_SSLKEYTYPE, WX_SSL_CERT_TYPE);
		curl_setopt($ch, CURLOPT_SSLKEY, getcwd() . '../' . WX_PATH_API_PEM_KEY);
		
        $ret = curl_exec($ch); 
        curl_close($ch); 
        
        $json = json_decode($ret, true); 
        if (is_array($json))
        {
            //根据openid和access_token查询用户信息 
            $access_token = $json['access_token']; 
            $oid = $json['openid']; 
        }

        $this->openid_cached = $oid;
        return $oid;
    }
    
    function to_array()
    {
        $ret = array("appid"            => $this->app_id(),
                     "mch_id"           => $this->mch_id(),
                     "body"             => $this->body,
                     "attach"           => $this->attach,
                     "notify_url"       => $this->notify_url,
                     "openid"           => $this->openid(),
                     "out_trade_no"     => $this->out_trade_no(),
                     "spbill_create_ip" => $this->spbill_create_ip(),
                     "total_fee"        => $this->total_fee,
                     "nonce_str"        => $this->nonce_str(),
                     "trade_type"       => $this->trade_type());
                     
        ksort($ret);
        return $ret;
    }
    
    function to_string()
    {
        $plain = null;
        foreach ($this->to_array() as $key => $val)
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
        foreach ($this->to_array() as $key => $val)
        {
            $xml .= "<$key>$val</$key>";
        }
        
        $sign_ret = $this->sign();
        $xml .= "<sign>$sign_ret</sign></xml>";
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

function srvc_pay_api_invoke_js($appid, $prepay_id, $nonceStr, $paySign)
{
    $timestamp = time();
    $js = "<script>
            function onBridgeReady()
            {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest', {
                    \"appId\" : \"$appid\",
                    \"timeStamp\" : \"$timestamp\",
                    \"nonceStr\" : \"$nonceStr\",
                    \"package\" : \"prepay_id=$prepay_id\",
                    \"signType\" : \"MD5\",
                    \"paySign\" : \"$paySign\"
                },
                function(res)
                {     
                    if(res.err_msg == \"get_brand_wcpay_request：ok\" ) 
                    {
                    } 
                }
            ); 
            }
            if (typeof WeixinJSBridge == \"undefined\")
            {
                if( document.addEventListener )
                {
                    document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                }
                else if (document.attachEvent)
                {
                    document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
                    document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                }
            }
            else
            {
                onBridgeReady();
            }
            </script>";
    
    return $js;
}

function srvc_pay_api_order($body, $fee_CNY, $openid = "", $attach = "", $notify_url = null)
{
    $pay_inf = new PayInfo();
    $pay_inf->body = $body;
    $pay_inf->total_fee = $fee_CNY;
    $pay_inf->attach = $attach;
    
    if ($notify_url == null)
    {
        $notify_url = PAY_API_DEFAULT_NOTIFY_URL; 
    }
    $pay_inf->notify_url = $notify_url;
    
    $req_xml = $pay_inf->to_xml_str();
    
    $err = null;
    $resp_xml = __curl_post_ssl(PAY_API_ORDER_URL, $req_xml, $err);
    
    $xml = simplexml_load_string($resp_xml, null, LIBXML_NOCDATA);
    $js_pay = srvc_pay_api_invoke_js($xml->appid, $xml->prepay_id, $xml->nonce_str, $xml->sign);
    
    return $js_pay;
}

?>

