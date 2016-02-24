<?php
    
function srvc_redis_dump_info()
{
    $redis = new Redis();
    $redis->connect('127.0.0.1');
    $redis_info = $redis->info();
    $redis->close();
    
    $json = json_encode($redis_info);
    echo "$json";
}
    
srvc_redis_dump_info();

?>