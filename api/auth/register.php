<?php
require_once __DIR__ . '/../../utils/cors.php';
applyCors();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../utils/auth_middleware.php';

$db = new Database();
$conn = $db->getConnection();

$input = getInput();
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$phone = trim($input['phone'] ?? '');
$role = trim($input['role'] ?? 'traveler');

$errors = [];
if (empty($name)) $errors[] = "Name is required";
if (empty($email)) $errors[] = "Email is required";
if (empty($password)) $errors[] = "Password is required";
if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
if (!in_array($role, ['traveler', 'owner'])) $errors[] = "Role must be traveler or owner";

if (!empty($errors)) {
    jsonResponse(["message" => "Validation failed", "errors" => $errors], 422);
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    jsonResponse(["message" => "Email already registered"], 409);
}

$password_hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password_hash, phone, role) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$name, $email, $password_hash, $phone, $role]);
$userId = $conn->lastInsertId();

session_regenerate_id(true);

$_SESSION['user_id'] = $userId;
$_SESSION['role'] = $role;
$_SESSION['name'] = $name;
$_SESSION['email'] = $email;

$stmt = $conn->prepare("SELECT id, name, email, phone, role, created_at FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

jsonResponse(["message" => "Registration successful", "user" => $user], 201);