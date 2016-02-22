<?php
    
function __lock_file_until_ms($fh, $mseconds)
{
    $ret = false;
    
    while ($fh)
    {
        $ret = flock($fh, LOCK_EX | LOCK_NB);
        if (!$ret && $mseconds > 0)
        {
            // lock not acquired
            usleep($mseconds * 1000);
            
            // retry once
            $mseconds = 0;
        }
        else
        {
            break;
        }
    }
    
    return $ret;
}
    
?>