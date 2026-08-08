<?php
require_once __DIR__ . '/utils/cors.php';
applyCors();

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/app/autoload.php';

$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/Backend';
$path = parse_url($requestUri, PHP_URL_PATH);

if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

$path = ltrim($path, '/');
$path = rtrim($path, '/');

$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    'api/auth/register'          => ['AuthController', 'register'],
    'api/auth/login'             => ['AuthController', 'login'],
    'api/auth/logout'            => ['AuthController', 'logout'],
    'api/auth/check-session'     => ['AuthController', 'checkSession'],
    'api/auth/check_session'     => ['AuthController', 'checkSession'],
    'api/auth/send-verification' => ['AuthController', 'sendVerification'],
    'api/auth/send_verification' => ['AuthController', 'sendVerification'],
    'api/auth/verify-email'      => ['AuthController', 'verifyEmail'],
    'api/auth/verify_email'      => ['AuthController', 'verifyEmail'],
    'api/hotels/list'            => ['HotelController', 'list'],
    'api/hotels/get'             => ['HotelController', 'get'],
    'api/hotels/create'          => ['HotelController', 'create'],
    'api/hotels/update'          => ['HotelController', 'update'],
    'api/hotels/delete'          => ['HotelController', 'delete'],
    'api/hotels/add_image'       => ['HotelController', 'addImage'],
    'api/hotels/delete_image'    => ['HotelController', 'deleteImage'],
    'api/hotels/deleteImage'    => ['HotelController', 'deleteImage'],
    'api/hotels/my'              => ['HotelController', 'my'],
    'api/hotels/search'          => ['HotelController', 'search'],
    'api/hotels/add_amenity'     => ['HotelController', 'addAmenity'],
    'api/hotels/delete_amenity'  => ['HotelController', 'deleteAmenity'],
    'api/rooms/list'             => ['RoomController', 'list'],
    'api/rooms/create'           => ['RoomController', 'create'],
    'api/rooms/update'           => ['RoomController', 'update'],
    'api/rooms/delete'           => ['RoomController', 'delete'],
    'api/bookings/create'        => ['BookingController', 'create'],
    'api/bookings/list-user'     => ['BookingController', 'listUser'],
    'api/bookings/list_user'     => ['BookingController', 'listUser'],
    'api/bookings/list-owner'    => ['BookingController', 'listOwner'],
    'api/bookings/list_owner'    => ['BookingController', 'listOwner'],
    'api/bookings/confirm'       => ['BookingController', 'confirm'],
    'api/bookings/cancel'        => ['BookingController', 'cancel'],
    'api/events/list'            => ['EventController', 'list'],
    'api/events/create'          => ['EventController', 'create'],
    'api/events/update'          => ['EventController', 'update'],
    'api/events/delete'          => ['EventController', 'delete'],
    'api/offers/list'            => ['OfferController', 'list'],
    'api/offers/create'          => ['OfferController', 'create'],
    'api/offers/update'          => ['OfferController', 'update'],
    'api/offers/delete'          => ['OfferController', 'delete'],
    'api/places/list'            => ['PlaceController', 'list'],
    'api/places/create'          => ['PlaceController', 'create'],
    'api/places/update'          => ['PlaceController', 'update'],
    'api/places/delete'          => ['PlaceController', 'delete'],
    'api/places/geocode'         => ['PlaceController', 'geocode'],
    'api/places/extract'         => ['PlaceController', 'extract'],
    'api/admin/hotels'           => ['AdminController', 'hotels'],
    'api/admin/delete-hotel'     => ['AdminController', 'deleteHotel'],
    'api/admin/delete_hotel'     => ['AdminController', 'deleteHotel'],
    'api/notifications/list'     => ['NotificationController', 'list'],
    'api/notifications/mark-read' => ['NotificationController', 'markRead'],
    'api/notifications/mark_read' => ['NotificationController', 'markRead'],
];

$routeFound = false;

if (isset($routes[$path])) {
    [$controller, $action] = $routes[$path];
    $ctrl = new $controller();
    $ctrl->$action();
    $routeFound = true;
}

if (!$routeFound) {
    $pathParts = explode('/', $path);
    for ($depth = 4; $depth >= 3; $depth--) {
        if (count($pathParts) >= $depth) {
            $routeKey = implode('/', array_slice($pathParts, 0, $depth));
            if (isset($routes[$routeKey])) {
                [$controller, $action] = $routes[$routeKey];
                $ctrl = new $controller();
                $ctrl->$action();
                $routeFound = true;
                break;
            }
        }
    }
}

if (!$routeFound) {
    http_response_code(404);
    echo json_encode(["message" => "Route not found", "path" => $path]);
}