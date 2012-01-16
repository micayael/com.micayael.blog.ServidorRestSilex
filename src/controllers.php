<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use src\Entities\Comment;

require_once (BASE_DIR . '/src/Entities/Comment.php');

$app->get('/ver-comentarios.{format}', function() use($app){
    
    $sql = "select 
            case 
                when c.comment_parent != 0 then c.comment_parent
                else c.comment_id
            end as hilo,
            c.comment_id, 
            c.comment_parent,
            c.comment_date,
            c.comment_author,
            c.comment_author_email,
            c.comment_author_url
        from wp_comments c
        where c.comment_approved = 1
        order by hilo, c.comment_date";
    
    $comentarios = $app['db']->fetchAll($sql);
    $comentarios = utf8_converter($comentarios);
    
    //-- Una vez encontrados los datos retornamos un código HTTP 200 - OK
    return new Response(json_encode($comentarios), 200); 
    
});

$app->post('/crear-comentario.{format}', function(Request $request) use($app){
    
    //-- Controlamos que los parámetros que deben lleguen por POST efectivamente
    //   lleguen y en el caso de que no lo hagan enviamos un error con código 
    //   400 - Solicitud incorrecta
    if (!$comment = $request->get('comment'))
    {
        return new Response('Parametros insuficientes', 400);
    }

    $c = new Comment();
    $c->comment_post_id = $comment['comment_post_id'];
    $c->comment_author = $comment['comment_author'];
    $c->comment_author_email = $comment['comment_author_email'];
    $c->comment_author_url = $comment['comment_author_url'];
    $c->comment_author_IP = $comment['comment_author_IP'];
    $c->comment_content = $comment['comment_content'];
    $c->comment_approved = $comment['comment_approved'];
    $c->comment_agent = $comment['comment_agent'];
    $c->comment_type = $comment['comment_type'];
    $c->comment_parent = $comment['comment_parent'];
    $c->user_id = $comment['user_id'];
    
    $sql = $c->getInsertSQL();
    
    $app['db']->exec($sql);
    
    //-- En caso de exito retornamos el código HTTP 201 - Creado
    return new Response('Comentario creado', 201);
    
});

$app->put('actualizar-comentario/{id}.{format}', function($id) use($app){
    
    //-- Controlamos que los parámetros que deben lleguen por POST efectivamente
    //   lleguen y en el caso de que no lo hagan enviamos un error con código 
    //   400 - Solicitud incorrecta
    //-- También podemos usar directamente la Injección de dependecias para 
    //   obtener el request del contenedor a diferencia del ejemplo anterior.
    
    if (!$comment = $app['request']->get('comment'))
    {
        return new Response('Parametros insuficientes', 400);
    }
    
    $sql = Comment::getSelectForExists($id);
    
    $comentario = $app['db']->fetchAll($sql);
    
    //-- En caso de no existir el comentario a modificar retornamos un código
    //   HTTP 404 - No encontrado
    if(empty($comentario))
    {
        return new Response('Comentario no encontrado.', 404);
    }
    
    $sql = Comment::getUpdateSQL($id, $comment['comment_content']);
    
    $app['db']->exec($sql);
    
    //-- En caso de exito retornamos el código HTTP 200 - OK
    return new Response("Comentario con ID: {$id} actualizado", 200);
    
});

$app->delete('eliminar-comentario/{id}.{format}', function($id) use($app){
    
    $sql = Comment::getSelectForExists($id);
    
    $comentario = $app['db']->fetchAll($sql);
    
    //-- En caso de no existir el comentario a eliminar retornamos un código
    //   HTTP 404 - No encontrado
    if(empty($comentario))
    {
        return new Response('Comentario no encontrado.', 404);
    }
    
    $sql = Comment::getDeleteSQL($id);
    
    $app['db']->exec($sql);
    
    //-- En caso de exito retornamos el código HTTP 200 - OK
    return new Response("Comentario con ID: {$id} eliminado", 200);
    
}); 

return $app;