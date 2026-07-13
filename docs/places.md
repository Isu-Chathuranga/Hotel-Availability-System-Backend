# Nearby Places API

**Base URL:** `http://localhost/Backend/api/places`

---

## GET /list

List all nearby places for a hotel.

**Authentication:** None (public)

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| hotel_id  | int  | Yes      | Hotel ID    |

**Response 200:**
```json
{
  "places": [
    {
      "id": 1,
      "hotel_id": 1,
      "name": "Eiffel Tower",
      "description": "Famous landmark",
      "location_url": "https://maps.google.com/...",
      "latitude": 48.8584,
      "longitude": 2.2945,
      "distance": "2.5 km",
      "category": "landmark"
    }
  ]
}
```

**Errors:** 400 (missing/invalid hotel_id)

---

## POST /create

Add a nearby place to a hotel.

**Authentication:** `owner` role required (must own the hotel)

**Request Body (JSON):**

| Parameter   | Type   | Required | Description              |
|-------------|--------|----------|--------------------------|
| hotel_id    | int    | Yes      | Hotel ID                 |
| name        | string | Yes      | Place name               |
| description | string | No       | Place description        |
| location_url| string | No       | Google Maps / location URL |
| latitude    | float  | No       | Latitude for map marker  |
| longitude   | float  | No       | Longitude for map marker |
| distance    | string | No       | Distance (e.g. "2.5 km") |
| category    | string | No       | e.g. restaurant, landmark |

**Response 201:**
```json
{ "message": "Place created successfully", "place": { "...full place object..." } }
```

**Errors:** 401/403 (not owner), 404 (hotel not found), 422 (validation)

---

## PUT /update

Update a nearby place.

**Authentication:** `owner` role required (must own the hotel)

**Request Body (JSON):**

| Parameter   | Type   | Required | Description        |
|-------------|--------|----------|--------------------|
| id          | int    | Yes      | Place ID           |
| name        | string | No       | Updated name       |
| description | string | No       | Updated description |
| location_url| string | No       | Updated location URL |
| latitude    | float  | No       | Updated latitude   |
| longitude   | float  | No       | Updated longitude  |
| distance    | string | No       | Updated distance   |
| category    | string | No       | Updated category   |

**Response 200:**
```json
{ "message": "Place updated successfully", "place": { "...full place object..." } }
```

**Errors:** 400 (missing id), 403 (not owner), 404 (not found), 422 (no fields)

---

## DELETE /delete

Delete a nearby place.

**Authentication:** `owner` role required (must own the hotel)

**Request Body (JSON):**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id        | int  | Yes      | Place ID    |

**Response 200:**
```json
{ "message": "Place deleted successfully" }
```

**Errors:** 400 (missing id), 403 (not owner), 404 (not found)
