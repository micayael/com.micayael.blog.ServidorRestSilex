<?php

//-- Definimos una constante para usarla durante todo el proyecto. Este PATH
//   hará referencia a la carpeta raíz del proyecto
define('BASE_DIR', realpath(__DIR__ . '/..'));

//-- Importamos el archivo src/app.php para la configuración del proyecto
$app = require_once (BASE_DIR . '/src/app.php');

//-- Configuramos el controlador frontal para que se encuentre en estado de debug
//   a fin de que nos entregue mayor información de los errores. Una vez que lo 
//   queramos poner en producción debemos ponerlo como false para que por 
//   seguridad solo muestre el mensaje de error configurado en el archivo app.php
$app['debug'] = true;

//-- Asignamos el usuario y contraseña para el acceso al servicio
$app['auth.user'] = AUTH_USER;
$app['auth.pass'] = AUTH_PASS;

//-- Ejecutamos la aplicación
$app->run();