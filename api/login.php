<?php
// api/login.php
// Handles the "Sign In" form (Email, Password)

require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, "Method not allowed", null, 405);
}

$input = json_decode(file_get_contents("php://input"), true);

$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if ($email === '' || $password === '') {
    sendResponse(false, "Email and password are required.", null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();

    $stmt = $db->prepare("SELECT id, full_name, email, password_hash FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        sendResponse(false, "Incorrect email or password.", null, 401);
    }

    // Store the logged-in user in the session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email']   = $user['email'];

    sendResponse(true, "Signed in successfully.", [
        "id"       => $user['id'],
        "fullName" => $user['full_name'],
        "email"    => $user['email']
    ], 200);

} catch (PDOException $e) {
    sendResponse(false, "Something went wrong while signing in.", null, 500);
}
