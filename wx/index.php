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

?>

<html>
<head>
<title>Welcome to Too-Studio!</title>
</head>
<body>

<?php
echo "Too-Studio!";
?>

</body>
</html>

