<?php

function utf8_converter($array)
{
    array_walk_recursive($array, function(&$item, $key){
        $item = utf8_encode($item);
    });
    
    return $array;
}