<?php

require 'RateLimiter.php';

$redis = new Redis();
$redis->connect('192.168.0.14', 6379);

$limiter = new RateLimiter($redis, 'demo', 20);
$limiter->create(8);
