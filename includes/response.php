<?php
// includes/response.php
// Small helper to send a consistent JSON response and stop execution.

function sendResponse($success, $message, $data = null, $statusCode = 200)
{
    http_response_code($statusCode);
    $payload = [
        "success" => $success,
        "message" => $message
    ];
    if ($data !== null) {
        $payload["data"] = $data;
    }
    echo json_encode($payload);
    exit();
}
