<?php

$redis = new Redis();
$redis->connect('192.168.0.14', 6379);

swoole_timer_tick(100, function() use ($redis) {
    var_dump($redis->rPop('demo'));
});

