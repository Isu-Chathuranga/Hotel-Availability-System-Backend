<?php
// api/me.php
// Returns the currently logged-in user based on the PHP session (if any)

require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../includes/response.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    sendResponse(false, "Not signed in.", null, 401);
}

sendResponse(true, "Session active.", [
    "id"    => $_SESSION['user_id'],
    "email" => $_SESSION['email']
]);
