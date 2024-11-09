<?php
namespace App\Utils;

class Util
{
    
    public static function is_assoc_array(array $array): bool {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

}