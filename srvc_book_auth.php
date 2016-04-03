<?php
    
require_once 'wx_dev.php';

// trace the visitor
log_visitor_info();

redirect_to_path_with_wx_auth("wx/srvc_book.php");
    
?>