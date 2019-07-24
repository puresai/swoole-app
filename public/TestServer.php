<?php 

require dirname(__DIR__) . '/vendor/autoload.php';

use Sai\Swoole\Weather;

$env = \Dotenv\Dotenv::create(dirname(__DIR__));
$env->load();

$str = '杭州天气';

echo Weather::getInfoLikeName('杭州');