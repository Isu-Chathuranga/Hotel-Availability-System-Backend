<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 86400);
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    if (!session_start()) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(["message" => "Failed to start session"]);
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isOwner() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'owner';
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(["message" => "Unauthorized. Please login first."]);
        exit;
    }
}

function requireOwner() {
    requireLogin();
    if (!isOwner()) {
        http_response_code(403);
        echo json_encode(["message" => "Access denied. Owner role required."]);
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(["message" => "Access denied. Admin role required."]);
        exit;
    }
}
