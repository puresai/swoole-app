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
        $redis->connect(getenv('REDIS_HOST', '127.0.0.1'), getenv('REDIS_PORT', 6379), 3);
        if ($auth = getenv('REDIS_PASSWORD', null)) {
            $redis->auth($auth);
        }
        $redis->select(getenv('REDIS_DB', 0));
        self::$redis = $redis;
    }

    public function __clone()
    {

    }
}