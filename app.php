<?php

use App\Application;

error_reporting('local' === getenv('APP_ENV')
    ? E_ALL
    : 0
);

define('TIME', time());

function autoloader(string $class)
{
    @include_once 'app' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
}

spl_autoload_register('autoloader');

$app = new Application();

register_shutdown_function([$app, 'fatalHandler']);

$app->run();