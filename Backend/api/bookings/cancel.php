<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../utils/auth_middleware.php';
require_once __DIR__ . '/../../utils/email.php';

requireOwner();

$db = new Database();
$conn = $db->getConnection();

$input = getInput();
$id = $input['id'] ?? $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    jsonResponse(["message" => "Booking ID is required"], 400);
}

$stmt = $conn->prepare(
    "SELECT b.*, h.name as hotel_name, h.owner_id, u.email as user_email
     FROM bookings b
     JOIN hotels h ON b.hotel_id = h.id
     JOIN users u ON b.user_id = u.id
     WHERE b.id = ?"
);
$stmt->execute([$id]);
$booking = $stmt->fetch();

if (!$booking) {
    jsonResponse(["message" => "Booking not found"], 404);
}

if ($booking['owner_id'] != $_SESSION['user_id']) {
    jsonResponse(["message" => "You can only cancel bookings for your own hotels"], 403);
}

if ($booking['status'] === 'cancelled') {
    jsonResponse(["message" => "Booking is already cancelled"], 400);
}

$stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
$stmt->execute([$id]);

$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->execute([$id]);
$updatedBooking = $stmt->fetch();

sendBookingStatusUpdate($booking['user_email'], $updatedBooking, 'cancelled');

jsonResponse(["message" => "Booking cancelled successfully", "booking" => $updatedBooking]);
