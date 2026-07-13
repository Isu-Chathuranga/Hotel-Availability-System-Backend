<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Hotel.php';

class AdminController extends Controller {
    private Hotel $hotelModel;

    public function __construct() {
        parent::__construct();
        $this->hotelModel = new Hotel();
    }

    public function hotels(): void {
        $this->requireAdmin();
        $hotels = $this->hotelModel->getAdminHotelList();
        $this->json(["hotels" => $hotels]);
    }

    public function deleteHotel(): void {
        $this->requireAdmin();
        $input = $this->getJsonInput();
        $id = $input['id'] ?? $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            $this->json(["message" => "Hotel ID is required"], 400);
        }

        $id = (int)$id;
        $hotel = $this->hotelModel->findById($id);
        if (!$hotel) {
            $this->json(["message" => "Hotel not found"], 404);
        }

        try {
            $this->hotelModel->beginTransaction();
            $this->hotelModel->query("DELETE FROM hotel_images WHERE hotel_id = ?", [$id]);
            $this->hotelModel->query("DELETE FROM bookings WHERE hotel_id = ?", [$id]);
            $this->hotelModel->query("DELETE FROM rooms WHERE hotel_id = ?", [$id]);
            $this->hotelModel->query("DELETE FROM events WHERE hotel_id = ?", [$id]);
            $this->hotelModel->query("DELETE FROM special_offers WHERE hotel_id = ?", [$id]);
            $this->hotelModel->query("DELETE FROM nearby_places WHERE hotel_id = ?", [$id]);
            $this->hotelModel->query("DELETE FROM hotels WHERE id = ?", [$id]);
            $this->hotelModel->commit();
            $this->json(["message" => "Hotel deleted successfully by admin"]);
        } catch (Exception $e) {
            $this->hotelModel->rollBack();
            $this->json(["message" => "Failed to delete hotel"], 500);
        }
    }
}
