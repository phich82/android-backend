<?php
namespace App\Utils;

class Util
{
    
    public static function is_assoc_array(array $array): bool {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    public static function getRequestBodyPost() {
        if (!empty($_POST)) {
            // when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request
            // NOTE: if this is the case and $_POST is empty, check the variables_order in php.ini! - it must contain the letter P
            return $_POST;
        }

        // when using application/json as the HTTP Content-Type in the request 
        $post = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $post;
        }

        return [];
    }

}