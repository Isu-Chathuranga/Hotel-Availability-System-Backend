<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: Get, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
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

$stmt = $conn->prepare(
    "SELECT b.*, h.name as hotel_name, r.room_type, u.name as user_name, u.email as user_email,
            u.phone as user_phone
     FROM bookings b
     JOIN hotels h ON b.hotel_id = h.id
     JOIN rooms r ON b.room_id = r.id
     JOIN users u ON b.user_id = u.id
     WHERE h.owner_id = ?
     ORDER BY b.created_at DESC"
);
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();

jsonResponse(["bookings" => $bookings]);
