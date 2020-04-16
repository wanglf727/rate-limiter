<?php

class RateLimiter
{
    /**
     * @var Redis
     */
    protected $redis;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $max;


    public function __construct(Redis $redis, string $name, int $max)
    {
        $this->redis = $redis;
        $this->name = $name;
        $this->max = $max;
    }

    protected function put(int $num): int
    {
        $size = $this->redis->lLen($this->name);
        $max = (int)$this->max;
        $num = ($max>=$size+$num) ? $num : $max-$size;
        if ($num > 0) {
            $tokens = array_fill(0, $num, 1);
            foreach ($tokens as $token) {
                $this->redis->lPush($this->name, $token);
            }
            return $num;
        }
        return 0;
    }

    public function create(int $num)
    {
        swoole_timer_tick(1000, function() use($num) {
            $res = $this->put($num);
        });
    }
}