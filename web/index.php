<?php

define('BASE_DIR', realpath(__DIR__ . '/..'));

$app = require_once (BASE_DIR . '/src/app.php');

$app['debug'] = true;

$app['auth.user'] = 'admin';
$app['auth.pass'] = '123456';

$app->run();