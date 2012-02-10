<?php

//-- Importa el framework silex
require_once (BASE_DIR . '/vendor/silex.phar');

//-- Crea una nueva aplicación silex
$app = new Silex\Application();

//-- Configuramos la base de datos
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options'        => array(
        'driver'        => DB_DRIVER,
        'host'          => DB_HOST,
        'dbname'        => DB_NAME,
        'user'          => DB_USER,
        'password'      => DB_PASS,
    ),
    'db.config' => array(),
    'db.dbal.class_path'    => BASE_DIR . '/vendor/doctrine-dbal/lib',
    'db.common.class_path'  => BASE_DIR . '/vendor/doctrine-common/lib',
));