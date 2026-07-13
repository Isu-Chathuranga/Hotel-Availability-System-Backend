<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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
$hotelId = $input['hotel_id'] ?? null;
$name = trim($input['name'] ?? '');
$description = trim($input['description'] ?? '');
$eventDate = $input['event_date'] ?? null;
$price = $input['price'] ?? null;

if (!$hotelId || !is_numeric($hotelId)) {
    jsonResponse(["message" => "Hotel ID is required"], 422);
}
if (empty($name)) {
    jsonResponse(["message" => "Event name is required"], 422);
}

$stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->execute([$hotelId]);
$hotel = $stmt->fetch();

if (!$hotel) {
    jsonResponse(["message" => "Hotel not found"], 404);
}

if ($hotel['owner_id'] != $_SESSION['user_id']) {
    jsonResponse(["message" => "You can only add events to your own hotels"], 403);
}

$stmt = $conn->prepare("INSERT INTO events (hotel_id, name, description, event_date, price) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$hotelId, $name, $description, $eventDate, $price]);

$eventId = $conn->lastInsertId();
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch();

jsonResponse(["message" => "Event created successfully", "event" => $event], 201);
