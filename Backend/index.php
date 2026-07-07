<?php
require_once __DIR__ . '/autoload.php';

use App\Config\Session;
use App\Middleware\CorsMiddleware;
use App\Utils\Router;

CorsMiddleware::handle();
Session::start();

$router = new Router();

$router->post('api/auth/register', 'AuthController@register');
$router->post('api/auth/register.php', 'AuthController@register');
$router->post('api/auth/login', 'AuthController@login');
$router->post('api/auth/login.php', 'AuthController@login');
$router->post('api/auth/logout', 'AuthController@logout');
$router->post('api/auth/logout.php', 'AuthController@logout');
$router->get('api/auth/check-session', 'AuthController@checkSession');
$router->get('api/auth/check_session', 'AuthController@checkSession');
$router->get('api/auth/check_session.php', 'AuthController@checkSession');

$router->post('api/auth/register-owner', 'AuthController@registerOwner');
$router->post('api/hotel-owner/register', 'AuthController@registerOwner');

$router->get('api/hotels/list', 'HotelController@index');
$router->get('api/hotels/list.php', 'HotelController@index');
$router->get('api/hotels/get', 'HotelController@show');
$router->get('api/hotels/get.php', 'HotelController@show');
$router->post('api/hotels/create', 'HotelController@store');
$router->post('api/hotels/create.php', 'HotelController@store');
$router->put('api/hotels/update', 'HotelController@update');
$router->put('api/hotels/update.php', 'HotelController@update');
$router->delete('api/hotels/delete', 'HotelController@destroy');
$router->delete('api/hotels/delete.php', 'HotelController@destroy');
$router->get('api/hotels/search', 'HotelController@search');
$router->get('api/hotels/search.php', 'HotelController@search');
$router->get('api/hotels/location-counts', 'HotelController@locationCounts');

$router->get('api/rooms/list', 'RoomController@index');
$router->get('api/rooms/list.php', 'RoomController@index');
$router->post('api/rooms/create', 'RoomController@store');
$router->post('api/rooms/create.php', 'RoomController@store');
$router->put('api/rooms/update', 'RoomController@update');
$router->put('api/rooms/update.php', 'RoomController@update');
$router->delete('api/rooms/delete', 'RoomController@destroy');
$router->delete('api/rooms/delete.php', 'RoomController@destroy');

$router->post('api/bookings/create', 'BookingController@store');
$router->post('api/bookings/create.php', 'BookingController@store');
$router->get('api/bookings/list-user', 'BookingController@listUser');
$router->get('api/bookings/list_user', 'BookingController@listUser');
$router->get('api/bookings/list_user.php', 'BookingController@listUser');
$router->get('api/bookings/list-owner', 'BookingController@listOwner');
$router->get('api/bookings/list_owner', 'BookingController@listOwner');
$router->get('api/bookings/list_owner.php', 'BookingController@listOwner');
$router->put('api/bookings/confirm', 'BookingController@confirm');
$router->put('api/bookings/confirm.php', 'BookingController@confirm');
$router->put('api/bookings/cancel', 'BookingController@cancel');
$router->put('api/bookings/cancel.php', 'BookingController@cancel');

$router->get('api/events/list', 'EventController@index');
$router->get('api/events/list.php', 'EventController@index');
$router->post('api/events/create', 'EventController@store');
$router->post('api/events/create.php', 'EventController@store');
$router->put('api/events/update', 'EventController@update');
$router->put('api/events/update.php', 'EventController@update');
$router->delete('api/events/delete', 'EventController@destroy');
$router->delete('api/events/delete.php', 'EventController@destroy');

$router->get('api/offers/list', 'OfferController@index');
$router->get('api/offers/list.php', 'OfferController@index');
$router->post('api/offers/create', 'OfferController@store');
$router->post('api/offers/create.php', 'OfferController@store');
$router->put('api/offers/update', 'OfferController@update');
$router->put('api/offers/update.php', 'OfferController@update');
$router->delete('api/offers/delete', 'OfferController@destroy');
$router->delete('api/offers/delete.php', 'OfferController@destroy');

$router->get('api/places/list', 'PlaceController@index');
$router->get('api/places/list.php', 'PlaceController@index');
$router->post('api/places/create', 'PlaceController@store');
$router->post('api/places/create.php', 'PlaceController@store');
$router->put('api/places/update', 'PlaceController@update');
$router->put('api/places/update.php', 'PlaceController@update');
$router->delete('api/places/delete', 'PlaceController@destroy');
$router->delete('api/places/delete.php', 'PlaceController@destroy');

$router->get('api/admin/hotels', 'AdminController@hotels');
$router->get('api/admin/hotels.php', 'AdminController@hotels');
$router->delete('api/admin/delete-hotel', 'AdminController@deleteHotel');
$router->delete('api/admin/delete_hotel', 'AdminController@deleteHotel');
$router->delete('api/admin/delete_hotel.php', 'AdminController@deleteHotel');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
