<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//------------------------------------------------------------------------------

require_once (BASE_DIR . '/src/bootstrap.php');
require_once (BASE_DIR . '/src/util.php');
$app = require(BASE_DIR . '/src/controllers.php');

//------------------------------------------------------------------------------

$app->before(function (Request $request) use ($app){
    
    $user = $request->server->get('PHP_AUTH_USER');
    $pass = $request->server->get('PHP_AUTH_PW');
    
    if($app['auth.user'] != $user || $app['auth.pass'] != $pass)
    {
        return new Response('Unauthorized', 403, array('WWW-Authenticate' => 'Basic realm="Autenticacion requerida"'));
    }
    
});

//------------------------------------------------------------------------------

$app->after(function(Request $request, Response $response) use ($app){
    
    $format = $request->get('format');
    
    switch($format)
    {
        case 'json' :
            $response->headers->set('Content-Type', 'application/json');
            break;
        case 'html' :
            $response->headers->set('Content-Type', 'text/html');
            break;
        default:
            $app->abort(404, "El formato '{$format}' solicitado no existe");
    }
    
});

//------------------------------------------------------------------------------

$app->error(function(\Exception $e, $code) use($app){
    
    switch($code)
    {
        case 404:
            $message = 'La página que estás buscando no existe.';
            break;
        default:
            $message = 'Ocurrió un error al procesar su solicitud.';
            break;
    }

    if ($app['debug'])
        return ;
    else
        return new Response($message, $code);
    
});

//------------------------------------------------------------------------------

return $app;