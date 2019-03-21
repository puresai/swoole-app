<?php 

require dirname(__DIR__) . '/vendor/autoload.php';

use Sai\Swoole\Chat;

$dotenv = \Dotenv\Dotenv::create(dirname(__DIR__));
$dotenv->load();

$options = [
    'daemonize' => true
];
$ws = new Chat($options);
$ws->start();
