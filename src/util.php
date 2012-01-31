<?php

/**
 * Recorre un array recursivamente y aplica a cada item la función utf8_encode
 * @param array $array
 * @return array
 */
function utf8_converter($array)
{
    array_walk_recursive($array, function(&$item, $key){
        $item = utf8_encode($item);
    });
    
    return $array;
}