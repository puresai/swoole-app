<?php
/**
 * User: wangzt
 * Date: 2019/3/21
 */

namespace Sai\Swoole;

use Medoo\Medoo;
use PDO;

class Redis
{
    public static $redis = null;

    public static function getInstance()
    {
        if (empty(self::$redis)) {
            self::init();
        }

        return self::$redis;
    }

    public static function init()
    {
        $redis = new \Redis;
        $redis->connect(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', 6379), 3);
        if ($auth = env('REDIS_PASSWORD', null)) {
            $redis->auth($auth);
        }
        $redis->select(env('REDIS_DB', 0));
        self::$redis = $redis;
    }

    public function __clone()
    {

    }
}