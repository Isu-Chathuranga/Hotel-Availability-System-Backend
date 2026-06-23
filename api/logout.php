<?php
// api/logout.php
// Destroys the current session

require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../includes/response.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

sendResponse(true, "Signed out successfully.");
