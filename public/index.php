<?php

require __DIR__.'/../vendor/autoload.php';

defined('APP_PATH') or define('APP_PATH', realpath(__DIR__.'/../app/'));
defined('APP_ENV') or define('APP_ENV', getenv('APP_ENV') ?: 'development');


$app = require APP_PATH.'/bootstrap.php';

$app->run();
