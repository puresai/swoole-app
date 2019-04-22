<?php 

require dirname(__DIR__) . '/vendor/autoload.php';

use Sai\Swoole\Chat;

$env = \Dotenv\Dotenv::create(dirname(__DIR__));
$env->load();

$options = [
    'daemonize' => true
];
$ws = new Chat($options, '0.0.0.0', getenv('SERVER_PORT', 9500));
$ws->start();