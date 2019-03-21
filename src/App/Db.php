<?php
/**
 * User: wangzt
 * Date: 2019/3/21
 */

namespace Sai\Swoole;

use Medoo\Medoo;
use PDO;

class Db
{
    public static $db = null;

    public static function getInstance()
    {
        if (empty(self::$db)) {
            self::init();
        }

        return self::$db;
    }

    public static function init()
    {
        self::$db = new Medoo([
            // required
            'database_type' => getenv('DB_TYPE'),
            'database_name' => getenv('DB_DATABASE'),
            'server' => getenv('DB_HOST'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),

            // [optional]
            'charset' => getenv('DB_CHARSET'),
            'port' => getenv('DB_PORT'),

            // [optional] Table prefix
            'prefix' => getenv('DB_PREFIX'),

            // [optional] Enable logging (Logging is disabled by default for better performance)
//            'logging' => false,

            // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
            'option' => [
//                PDO::ATTR_CASE => PDO::CASE_NATURAL,
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ]);
    }

    public function __clone()
    {

    }
}