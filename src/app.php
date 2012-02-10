<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//------------------------------------------------------------------------------

require_once (BASE_DIR . '/src/config.php');
require_once (BASE_DIR . '/src/bootstrap.php');
require_once (BASE_DIR . '/src/util.php');
$app = require(BASE_DIR . '/src/controllers.php');

//------------------------------------------------------------------------------

/**
 * Este método se ejecuta ANTES de cualquier action definido en el 
 * archivo src/controllers.php 
 * 
 * En este caso controla la autenticación utilizando "HTTP Basic Authentication",
 * en caso de no haber recibido el usuario y contraseña correctamente se 
 * devuelve el código de error 403 - Unauthorized. En caso de no querer controlar 
 * el servicio por medio de un usuario y contraseña se podría comentar el contenido 
 * de este método.
 */
$app->before(function (Request $request) use ($app){
    
    $user = $request->server->get('PHP_AUTH_USER');
    $pass = $request->server->get('PHP_AUTH_PW');
    
    if($app['auth.user'] != $user || $app['auth.pass'] != $pass)
    {
        return new Response('Unauthorized', 403, array('WWW-Authenticate' => 'Basic realm="Autenticacion requerida"'));
    }
    
});

//------------------------------------------------------------------------------

/**
 * Este método se ejecuta DESPUÉS de cualquier action definido en el 
 * archivo src/controllers.php 
 * 
 * Controla la extensión (format) que usemos al solicitar una URL para devolver
 * el content type más adecuado. En caos de no definirse una extensión correcta 
 * devolverá el código de error 404 - Página no existente.
 */
$app->after(function(Request $request, Response $response) use ($app){
    
    $format = $request->get('format');
    
    switch($format)
    {
        case 'json' :
            $response->headers->set('Content-Type', 'application/json');
            break;
        case 'xml' :
            $response->headers->set('Content-Type', 'application/xml');
            break;
        case 'html' :
            $response->headers->set('Content-Type', 'text/html');
            break;
        default:
            $app->abort(404, "El formato '{$format}' solicitado no existe");
    }
    
});

//------------------------------------------------------------------------------

/**
 * Este método se encarga de la gestión de errores del sitio que no serán 
 * manejadas y controladas por nosotros como un catch general.
 */
$app->error(function(\Exception $e, $code) use($app){
    
    //-- En caso de que la aplicación este marcada como modo DEBUG se dejará
    //   al framework devolver el mensaje de error con mayor información
    if ($app['debug'])
        return ;
    
    //-- En caso de no estar como DEBUG, es decir en producción, se evaluará 
    //   el código de error retornado y mostrará un mensaje mucho más pequeño.
    switch($code)
    {
        case 404:
            $message = 'La página que estás buscando no existe.';
            break;
        default:
            $message = 'Ocurrió un error al procesar su solicitud.';
            break;
    }

    return new Response($message, $code);
    
});

//------------------------------------------------------------------------------

//-- Finalmente retornamos la aplicación con todas estas configuraciones
//   para ser recepcionada y ejecutada en el controlador frontal (web/index.php)
return $app;