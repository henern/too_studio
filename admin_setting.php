<?php
require_once "wx_dev.php";
utils_init();
log_visitor_info();     # trace the visitor
    
require_once 'srvc_book.php';

?>
<html>
    <head>
        <title>Too塗画室管理后台</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="format-detection" content="email=no" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <script src="./js/book-toolkit.js"></script>
        <link rel="stylesheet" href="./css/base.css" type="text/css"/>
        <link rel="stylesheet" href="./css/book-default.css" type="text/css"/>

        <script>
        </script>
    </head>
    <body>
        <h3>休业日期</h3>
        <?php
        for ($k = 0; $k < 7 * 4; $k++)
        {
            $clock_cur = time() + $k * SEC_PER_DAY;
            $date_str = date("Ymd", $clock_cur);
            
            if (srvc_book_is_blocked($date_str))
            {
                echo "<s>" . $date_str . "</s>";
            }
            else
            {
                echo "<b>" . $date_str . "</b>";
            }
            
            echo "  " . 
                 "<a href=\"javascript:book_do_block('$date_str',null)\">锁定</a>" . 
                 " | " . 
                 "<a href=\"javascript:book_do_unblock('$date_str',null)\">恢复</a>" . 
                 "</br>";
        }
        ?>
    </body>
</html>