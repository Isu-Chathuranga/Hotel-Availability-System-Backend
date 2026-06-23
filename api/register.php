<?php
// api/register.php
// Handles the "Create an Account" form (Full Name, Email, Password)

require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, "Method not allowed", null, 405);
}

$input = json_decode(file_get_contents("php://input"), true);

$fullName = trim($input['fullName'] ?? '');
$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

// --- Validation ---
if ($fullName === '' || $email === '' || $password === '') {
    sendResponse(false, "Full name, email and password are all required.", null, 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, "Please enter a valid email address.", null, 400);
}

if (strlen($password) < 6) {
    sendResponse(false, "Password must be at least 6 characters long.", null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // Check if the email is already registered
    $checkStmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $checkStmt->execute([':email' => $email]);

    if ($checkStmt->fetch()) {
        sendResponse(false, "An account with this email already exists.", null, 409);
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $db->prepare(
        "INSERT INTO users (full_name, email, password_hash) VALUES (:full_name, :email, :password_hash)"
    );
    $stmt->execute([
        ':full_name'     => $fullName,
        ':email'         => $email,
        ':password_hash' => $passwordHash
    ]);

    sendResponse(true, "Account created successfully.", [
        "id"       => $db->lastInsertId(),
        "fullName" => $fullName,
        "email"    => $email
    ], 201);

} catch (PDOException $e) {
    sendResponse(false, "Something went wrong while creating the account.", null, 500);
}
