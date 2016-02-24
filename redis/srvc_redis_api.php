<?php
    
// test code
$redis = new Redis();
$redis->connect('127.0.0.1');
$redis_info = $redis->info();
var_dump($redis_info);
    
?>