<?php
    
require_once 'wx_dev.php';
utils_init();
log_visitor_info();

$post_raw = $GLOBALS["HTTP_RAW_POST_DATA"];

$xml = (array)simplexml_load_string($post_raw, null, LIBXML_NOCDATA);
log_pay_info(array_to_string($xml) . "\n" . array_to_string($_GET));

echo "<xml>
      <return_code><![CDATA[SUCCESS]]></return_code>
      <return_msg><![CDATA[OK]]></return_msg>
      </xml>";
    
?>