<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use src\Entities\Comment;

require_once (BASE_DIR . '/src/Entities/Comment.php');


$app->get('/ver-comentarios.{format}', function() use($app){
    
    $sql = Comment::findAll();
    
    //-- Obtenemos los datos de la base de datos y aplicamos el utf8_encode() 
    //   a cada item del array llamado a nuestro método utf8_converter() definido
    //   en src\util.php
    $comentarios = $app['db']->fetchAll($sql);
    $comentarios = utf8_converter($comentarios);
    
    //-- Una vez encontrados los datos, los retornamos con un código HTTP 200 - OK
    return new Response(json_encode($comentarios), 200); 
    
});

$app->post('/crear-comentario.{format}', function(Request $request) use($app){
    
    //-- Controlamos que los parámetros que deben llegar por POST efectivamente
    //   lleguen y en el caso de que no lo hagan enviamos un error con código 
    //   400 - Solicitud incorrecta
    if (!$comment = $request->get('comment'))
    {
        return new Response('Parametros insuficientes', 400);
    }

    //-- Utilizamos como ejemplo un objeto Comentario para delegar la creación 
    //   del SQL utilizando el método PDO::quote() para no tener problemas con 
    //   SQL Injection.
    $c = new Comment();
    $c->author = $app['db']->quote($comment['author']);
    $c->email = $app['db']->quote($comment['email']);
    $c->content = $app['db']->quote($comment['content']);
    
    $sql = $c->getInsertSQL();
    
    //-- Ejecutamos la sentencia
    $app['db']->exec($sql);
    
    //-- En caso de exito retornamos el código HTTP 201 - Creado
    return new Response('Comentario creado', 201);
    
});

$app->put('actualizar-comentario/{id}.{format}', function($id) use($app){
    
    //-- Controlamos que los parámetros que deben llegar por POST efectivamente
    //   lleguen y en el caso de que no lo hagan enviamos un error con código 
    //   400 - Solicitud incorrecta
    //-- También podemos usar directamente la Injección de dependecias para 
    //   obtener el request del contenedor a diferencia del ejemplo anterior.
    
    if (!$comment = $app['request']->get('comment'))
    {
        return new Response('Parametros insuficientes', 400);
    }
    
    //-- Obtenemos el select para encontrar un comentario de acuerdo al $id y
    //   comprobar que lo que vamos a modificar realmente exista.
    $sql = Comment::find($id);
    
    $comentario = $app['db']->fetchAll($sql);
    
    //-- En caso de no existir el comentario a modificar retornamos un código
    //   HTTP 404 - No encontrado
    if(empty($comentario))
    {
        return new Response('Comentario no encontrado.', 404);
    }
    
    //-- Si existe el comentario a modificar obtenemos el SQL para el update y
    //   lo ejecutamos
    $content = $app['db']->quote($comment['content']);
    $sql = Comment::getUpdateSQL($id, $content);
    
    //-- Ejecutamos la sentencia
    $app['db']->exec($sql);
    
    //-- En caso de exito retornamos el código HTTP 200 - OK
    return new Response("Comentario con ID: {$id} actualizado", 200);
    
});

$app->delete('eliminar-comentario/{id}.{format}', function($id) use($app){
    
    //-- Obtenemos el select para encontrar un comentario de acuerdo al $id y
    //   comprobar que lo que vamos a eliminar realmente exista.
    $sql = Comment::find($id);
    
    $comentario = $app['db']->fetchAll($sql);
    
    //-- En caso de no existir el comentario a eliminar retornamos un código
    //   HTTP 404 - No encontrado
    if(empty($comentario))
    {
        return new Response('Comentario no encontrado.', 404);
    }
    
    //-- Obtenemos el SQL para eliminar el comentario y ejecutamos la sentencia
    $sql = Comment::getDeleteSQL($id);
    
    $app['db']->exec($sql);
    
    //-- En caso de exito retornamos el código HTTP 200 - OK
    return new Response("Comentario con ID: {$id} eliminado", 200);
    
}); 

return $app;