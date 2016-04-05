<?php
    
require_once "wx_dev.php";
utils_init();

# trace the visitor
log_visitor_info();

?>
<html>
<body>
<p>任何问题请致电<a href="tel:<?php echo TOO_WX_TEL_KEFU_A ?>">林小姐</a>。</p>
我们的地址：<a href="<?php echo TOO_WX_MAP_URL ?>"><?php echo TOO_WX_ADDRESS ?></a>。
</body>
</html>