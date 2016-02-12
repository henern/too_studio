<?php

require "wx_dev.php";

utils_init();

# trace the visitor
log_visitor_info();

# echo if need
if (too_wx_echo_if_require())
{
    #echo done.
    return;
}

// jump to book.php
Header("HTTP/1.1 303 See Other"); 
Header("Location: book.php"); 
exit;

?>


