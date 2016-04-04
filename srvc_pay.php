<?php
    
require_once "srvc_pay_api.php";
    
utils_init();
    
// trace the visitor
log_visitor_info();

// handle special action first
if (array_key_exists("action", $_GET))
{
    $action = $_GET["action"];
    if ($action == "query_ttoken")
    {
        $ttoken = $_GET["ttoken"];
        $json = impl_srvc_pay_api_get_notification($ttoken);
        echo "$json";
        exit;
    }
}

// by default, try to pay by wx
define("TOO_WX_PRICE_PER_PERSON_SMALL",     18800);
define("TOO_WX_PRICE_PER_PERSON_MED",       21800);
define("TOO_WX_PRICE_PER_PERSON_BIG",       25800);
define("TOO_WX_PRICE_PER_PERSON_DEFAULT",   100);

$count = $_GET["count"] + 0;
$discount_rate = 0.95;
$visit_day=$_GET["visit_day"];
$time_slot = $_GET["time_slot"];
$phone = $_GET["phone"];
$ttoken = $_GET["ttoken"];

$small_board    = array_number4key($_GET, "small_b");
$medium_board   = array_number4key($_GET, "medium_b");
$large_board    = array_number4key($_GET, "large_b");
$board_count    = $small_board + $medium_board + $large_board;

$small_price    = TOO_WX_PRICE_PER_PERSON_DEFAULT;
$medium_price   = TOO_WX_PRICE_PER_PERSON_DEFAULT;
$large_price    = TOO_WX_PRICE_PER_PERSON_DEFAULT;

$js_pay = "";
if ($count > 0 && 
    $board_count > 0 && 
    $small_price > 0 && $medium_price > 0 && $large_price > 0 && 
    strlen($ttoken) > 16)
{
    $total = $small_price   * $small_board + 
             $medium_price  * $medium_board + 
             $large_price   * $large_board;
    
    // any discount?
    $total *= $discount_rate;
    
    $param = array("C"              => "$count",
                   "T"              => "$total",
                   "VD"             => "$visit_day",
                   "TS"             => "$time_slot",
                   "BS"             => "$small_board",
                   "BM"             => "$medium_board",
                   "BL"             => "$large_board",
                   "PH"             => "$phone");
    $json = json_encode($param);
                   
    // X大Y中Z小
    $board_tips = "";
    if ($large_board > 0)   $board_tips = $board_tips . "$large_board" . "大";
    if ($medium_board > 0)  $board_tips = $board_tips . "$medium_board" . "中";
    if ($small_board > 0)   $board_tips = $board_tips . "$small_board" . "小";
    
    $pay_body = "Too塗画室" . "$count" . "人券（" . "$board_tips" . "）";
    $js_pay = srvc_pay_api_order($pay_body, 
                                 $total, 
                                 $ttoken,
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
        <div class="title-fullwidth" id="div_pay_status"><?php echo "$pay_body" ?>，付款中...</div>
    </header>

    <section class="content">
        <div align=center>
            <label id="label_trade_token"></label>
            </br><span>如果您的计划有变，请联系客服小妹全额退款 +86-18050786135</span>
            <img src='./img/too-icon.jpeg'/>
        </div>
    </section>
</bdoy>
</html>
