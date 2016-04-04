<?php
    
/*
    Tips to work with sendmail on CentOS7
    1. make sure sendmail is installed
        #yum install -y sendmail
        #yum install -y sendmail-cf
    2. add ip-to-host mapping in /etc/hosts
        120.2x.2xx.3x too-studio.cn
    3. update the hostname
        hostname too-studio.cn
    4. restart sendmail
        service sendmail restart
*/
    
function noreply_email($host)
{
    return "no-reply@" . $host;
}

function notify_email($host)
{
    return "notify@" . $host;
}

function email_send_to_many($to_arr, $subject, $msg, $from = null)
{
    $to = "";
    for ($k = 0; $k < count($to_arr); $k++)
    {
        if (strlen($to) > 0)    $to .= ", ";
        $to .= $to_arr[$k];
    }
    
    return email_send_to($to, $subject, $msg, $from);
}
    
function email_send_to($to, $subject, $msg, $from = null)
{
    $message = $msg;
    
    $headers = "";
    if ($from != null)
    {
        $headers = "From: " . $from . "\r\n" .
                   "Content-type: text/plain; charset=utf-8\r\n" .
                   "X-Mailer: PHP/" . phpversion();
    }

    return mail($to, $subject, $message, $headers);
}

?>