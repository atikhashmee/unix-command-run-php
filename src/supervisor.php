<?php

require_once 'vendor/autoload.php';


use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('mylogpath');
// var_dump($log);
$log->pushHandler(new StreamHandler('logs/log.log', Level::Warning));

// // add records to the log
$log->warning('Foo');
$log->error('Bar');