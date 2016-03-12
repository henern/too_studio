<?php
    
require "srvc_pay_api.php"
    
define("TOO_WX_PRICE_PER_PERSON_SMALL",     18800);
define("TOO_WX_PRICE_PER_PERSON_MED",       21800);
define("TOO_WX_PRICE_PER_PERSON_BIG",       25800);
define("TOO_WX_PRICE_PER_PERSON_DEFAULT",   1);

$count = $_GET["count"] + 0;
$price = TOO_WX_PRICE_PER_PERSON_DEFAULT;
$discount_rate = 1.0;
$visit_day=$_GET["visit_day"];
$time_slot = $_GET["time_slot"];
$phone = $_GET["phone"];

$js_pay = "";
if ($count > 0 && $price > 0)
{
    $total = $count * $price * $discount_rate;
    
    $param = array("count"          => "$count",
                   "total"          => "$total",
                   "visit_day"      => "$visit_day",
                   "time_slot"      => "$time_slot"
                   "phone"          => "$phone");
    $json = json_encode($param);
                   
    $js_pay = srvc_pay_api_order("Too塗画室$count人券", 
                                 $total, 
                                 "",   // ???
                                 $json,
                                 TOO_HOME_URL . "/wx/srvc_pay_api_notify.php");
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="email=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="stylesheet" href="./css/base.css" type="text/css"/>
    <link rel="stylesheet" href="./css/book-default.css" type="text/css"/>
    
    <title>微信付款</title>
    
    <?php echo $js_pay ?>
</head>
<body>
    <header>
        <div class="title-fullwidth">正在准备付款，请稍等...</div>
    </header>

    <section class="content">
        <div align=center>
            <img src='./img/too-icon.jpeg'/>
        </div>
    </section>
</bdoy>
</html>
