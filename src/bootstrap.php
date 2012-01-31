<?php

//-- Importa el framework silex
require_once (BASE_DIR . '/vendor/silex.phar');

//-- Crea una nueva aplicación silex
$app = new Silex\Application();

//-- Configuramos la base de datos
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options'        => array(
        'driver'        => 'pdo_mysql',
        'host'          => 'localhost',
        'dbname'        => 'restsilex',
        'user'          => 'root',
        'password'      => 'root',
    ),
    'db.config' => array(),
    'db.dbal.class_path'    => BASE_DIR . '/vendor/doctrine-dbal/lib',
    'db.common.class_path'  => BASE_DIR . '/vendor/doctrine-common/lib',
));