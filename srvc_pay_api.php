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
    // 交易唯一token
    var $trade_token;
    
    function __construct($ttoken)
    {
        $this->code_cached = "";
        $this->openid_cached = "";
        $this->trade_token = $ttoken;
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
        return $this->trade_token;
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
        
        $access_token = "";
        $oid = "";
        wx_openid_from_code($code, $access_token, $oid);

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
    
    function sign()
    {
        return wx_sign_array($this->to_array(), $this->pay_api_sign_key());
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
    
    function is_valid()
    {
        return ($this->openid() != "");
    }
}

function srvc_pay_api_invoke_js($appid, $prepay_id, $nonceStr, $ttoken)
{
    $timestamp = time();
    $req_array = array("appId"          => $appid,
                       "timeStamp"      => "$timestamp",
                       "nonceStr"       => $nonceStr,
                       "package"        => "prepay_id=$prepay_id",
                       "signType"       => "MD5");
    
    $paySign = wx_sign_array($req_array, TOO_WX_PAY_API_SIGN_KEY, true);
    $req_array["paySign"] = $paySign;
    $json = json_encode($req_array);
    
    $js = "<script>
            function onBridgeReady()
            {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest', 
                $json,
                function(res)
                {     
					var div_pay_status = document.getElementById(\"div_pay_status\");
                    if(res.err_msg == \"get_brand_wcpay_request:ok\" ) 
                    {
						div_pay_status.innerHTML = \"付款成功，恭候大驾。\";
                    }
					 
                    else if(res.err_msg == \"get_brand_wcpay_request:cancel\" ) 
                    {
						div_pay_status.innerHTML = \"付款已取消，您可以到店支付。\";
                    } 
					
                    else if(res.err_msg == \"get_brand_wcpay_request:fail\" ) 
                    {
						div_pay_status.innerHTML = \"付款失败，请联系客服小妹。\";
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

function srvc_pay_api_order($body, $fee_CNY, $trade_token, $openid = "", $attach = "", $notify_url = null)
{
    $pay_inf = new PayInfo($trade_token);
    $pay_inf->body = $body;
    $pay_inf->total_fee = $fee_CNY;
    $pay_inf->attach = $attach;
    
    if ($notify_url == null)
    {
        $notify_url = PAY_API_DEFAULT_NOTIFY_URL; 
    }
    $pay_inf->notify_url = $notify_url;
    
    $req_xml = $pay_inf->to_xml_str();
    
    if ($pay_inf->is_valid() == false)
    {
        return "";
    }
    
    $err = null;
    $resp_xml = __curl_post_ssl(PAY_API_ORDER_URL, $req_xml, $err);
    
    $xml = (array)simplexml_load_string($resp_xml, null, LIBXML_NOCDATA);
    $js_pay = srvc_pay_api_invoke_js(TOO_WX_APPID, $xml["prepay_id"], $xml["nonce_str"], $trade_token);
    
    return $js_pay;
}

define("REDIS_DB_INDEX_SRVC_PAY_API",       10);
define("REDIS_DB_HOST_IP",                  "127.0.0.1");
function impl_srvc_pay_api_archive_notification($xml_json, $key)
{
    $redis = new Redis();
    $redis->connect(REDIS_DB_HOST_IP);
    $redis->select(REDIS_DB_INDEX_SRVC_PAY_API);
    
    if ($redis->get($key) == FALSE)
    {
        $redis->set($key, $xml_json);
        $redis->bgSave();
    }
    
    $redis->close();
}

function impl_srvc_pay_api_get_notification($key)
{
    $redis = new Redis();
    $redis->connect(REDIS_DB_HOST_IP);
    $redis->select(REDIS_DB_INDEX_SRVC_PAY_API);
    
    $ret = $redis->get($key);
    $redis->close();
    
    return $ret;
}

?>

