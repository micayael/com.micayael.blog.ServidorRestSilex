<?php

namespace src\Entities;

/**
 * Clase creada para simular la encapsulación de la creación de la sentencia SQL.
 * IMPORTANTE: No se estám previendo controles para no permitir SQL Injection 
 */
class Comment 
{
    
    public $comment_post_id;
    public $comment_author;
    public $comment_author_email;
    public $comment_author_url;
    public $comment_author_IP;
    public $comment_content;
    public $comment_approved;
    public $comment_agent;
    public $comment_type;
    public $comment_parent;
    public $user_id;
    
    public function getInsertSQL()
    {
        $sql = "INSERT INTO wp_comments(
                comment_post_id, 
                comment_author, 
                comment_author_email, 
                comment_author_url, 
                comment_author_IP, 
                comment_content, 
                comment_approved, 
                comment_agent, 
                comment_type, 
                comment_parent, 
                user_id
            ) 
            VALUES (%d, '%s', '%s', '%s', '%s', '%s', %d, '%s', '%s', %d, %d)";
        
        $sql = sprintf(
            $sql, 
            $this->comment_post_id,
            $this->comment_author,
            $this->comment_author_email,
            $this->comment_author_url,
            $this->comment_author_ip,
            $this->comment_content,
            $this->comment_approved,
            $this->comment_agent,
            $this->comment_type,
            $this->comment_parent,
            $this->user_id
        );
        
        return $sql;
    }
    
    public static function getSelectForExists($id)
    {
        $sql = "select * 
                from wp_comments
                where comment_id = %d";
        $sql = sprintf($sql, $id);
        
        return $sql;
    }
    
    public static function getUpdateSQL($id, $content)
    {
        $sql = "update wp_comments
                set comment_content = '%s'
                where comment_id = %d";
        $sql = sprintf($sql, $content, $id);
        
        return $sql;
    }
    
    public static function getDeleteSQL($id)
    {
        $sql = "delete from wp_comments
                where comment_id = %d";
        $sql = sprintf($sql, $id);
        
        return $sql;
    }
    
}

?>
