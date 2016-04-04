<?php
    
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
                   "X-Mailer: PHP/" . phpversion();
    }

    return mail($to, $subject, $message, $headers);
}

?>