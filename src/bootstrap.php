<?php

require_once (BASE_DIR . '/vendor/silex.phar');

$app = new Silex\Application();

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options'        => array(
        'driver'        => 'pdo_mysql',
        'host'          => 'localhost',
        'dbname'        => 'blog',
        'user'          => 'root',
        'password'      => 'root',
    ),
    'db.config' => array(),
    'db.dbal.class_path'    => BASE_DIR . '/vendor/doctrine-dbal/lib',
    'db.common.class_path'  => BASE_DIR . '/vendor/doctrine-common/lib',
));