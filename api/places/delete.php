<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../utils/auth_middleware.php';

requireOwner();

$db = new Database();
$conn = $db->getConnection();

$input = getInput();
$id = $input['id'] ?? $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    jsonResponse(["message" => "Place ID is required"], 400);
}

$stmt = $conn->prepare("SELECT p.*, h.owner_id FROM nearby_places p JOIN hotels h ON p.hotel_id = h.id WHERE p.id = ?");
$stmt->execute([$id]);
$place = $stmt->fetch();

if (!$place) {
    jsonResponse(["message" => "Place not found"], 404);
}

if ($place['owner_id'] != $_SESSION['user_id']) {
    jsonResponse(["message" => "You can only delete places in your own hotels"], 403);
}

$conn->prepare("DELETE FROM nearby_places WHERE id = ?")->execute([$id]);

jsonResponse(["message" => "Place deleted successfully"]);
