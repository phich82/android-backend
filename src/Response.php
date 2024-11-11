<?php
namespace App;

use InvalidArgumentException;


class Response
{
    public static function success(mixed $data = null, string $message = "Success", string $code = "200"): string {
        return static::json(true, $code, $message, $data);
    }

    public static function error(string $message = "Bad Request", string $code = "400"): string {
        return static::json(false, $code, $message, null);
    }

    private static function json(bool $success, string $code = "", string $message = "", mixed $data = null): string {
        ob_start();
        
        // Clear json_last_error()
        json_encode(null);

        http_response_code($code);
        $json = json_encode([
            "success" => $success,
            "message" => $message,
            "code" => $code,
            "data" => $data
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(sprintf(
                'Unable to encode data to JSON in %s: %s',
                __CLASS__,
                json_last_error_msg()
            ));
        }

        echo $json;
        header("Content-Type: application/json");
        ob_end_flush(); //now the headers are sent
        exit;
    }
}