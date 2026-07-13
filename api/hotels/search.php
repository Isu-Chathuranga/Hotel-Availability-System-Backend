<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
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
require_once __DIR__ . '/../../utils/auth_middleware.php';

$db = new Database();
$conn = $db->getConnection();

$location = $_GET['location'] ?? '';
$checkIn = $_GET['check_in'] ?? '';
$checkOut = $_GET['check_out'] ?? '';
$rooms = $_GET['rooms'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';
$rating = $_GET['rating'] ?? '';
$travelPurpose = $_GET['travel_purpose'] ?? '';
$event = $_GET['event'] ?? '';

$sql = "SELECT DISTINCT h.*, u.name as owner_name,
    (SELECT MIN(price) FROM rooms WHERE hotel_id = h.id AND is_available = 1) as min_room_price
    FROM hotels h
    JOIN users u ON h.owner_id = u.id
    LEFT JOIN rooms r ON r.hotel_id = h.id
    LEFT JOIN events e ON e.hotel_id = h.id
    WHERE h.status = 'active'";

$params = [];

if (!empty($location)) {
    $sql .= " AND (h.location LIKE ? OR h.address LIKE ? OR h.name LIKE ?)";
    $locTerm = "%{$location}%";
    $params[] = $locTerm;
    $params[] = $locTerm;
    $params[] = $locTerm;
}

if (!empty($minPrice)) {
    $sql .= " AND (SELECT MIN(price) FROM rooms WHERE hotel_id = h.id AND is_available = 1) >= ?";
    $params[] = (float)$minPrice;
}

if (!empty($maxPrice)) {
    $sql .= " AND (SELECT MIN(price) FROM rooms WHERE hotel_id = h.id AND is_available = 1) <= ?";
    $params[] = (float)$maxPrice;
}

if (!empty($rating)) {
    $sql .= " AND h.rating >= ?";
    $params[] = (float)$rating;
}

if (!empty($travelPurpose)) {
    $sql .= " AND h.travel_purpose LIKE ?";
    $params[] = "%{$travelPurpose}%";
}

if (!empty($event)) {
    $sql .= " AND e.name LIKE ?";
    $params[] = "%{$event}%";
}

if (!empty($checkIn) && !empty($checkOut)) {
    $sql .= " AND h.id NOT IN (
        SELECT b.hotel_id FROM bookings b
        WHERE b.status IN ('pending', 'confirmed')
        AND (b.check_in < ? AND b.check_out > ?)
    )";
    $params[] = $checkOut;
    $params[] = $checkIn;
}

$sql .= " ORDER BY h.rating DESC, h.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$hotels = $stmt->fetchAll();

foreach ($hotels as &$hotel) {
    $imgStmt = $conn->prepare("SELECT image_url FROM hotel_images WHERE hotel_id = ? LIMIT 1");
    $imgStmt->execute([$hotel['id']]);
    $image = $imgStmt->fetch();
    $hotel['image'] = $image ? $image['image_url'] : null;
}
unset($hotel);

jsonResponse(["hotels" => $hotels]);
