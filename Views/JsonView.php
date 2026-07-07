<?php
namespace App\Views;

class JsonView
{
    public static function render($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function success($data = null, string $message = "Success", int $statusCode = 200): void
    {
        self::render([
            "success" => true,
            "message" => $message,
            "data"    => $data,
        ], $statusCode);
    }

    public static function error(string $message, int $statusCode = 400, $errors = null): void
    {
        $response = ["message" => $message];
        if ($errors !== null) {
            $response["errors"] = $errors;
        }
        self::render($response, $statusCode);
    }
}
