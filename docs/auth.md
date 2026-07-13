# Auth API

**Base URL:** `http://localhost/Backend/api/auth`

---

## POST /register

Register a new user account.

**Authentication:** None (public)

**Request Body (JSON):**

| Parameter  | Type   | Required | Description                        |
|------------|--------|----------|------------------------------------|
| name       | string | Yes      | User's full name                   |
| email      | string | Yes      | Valid email address (must be unique) |
| password   | string | Yes      | Minimum 6 characters               |
| phone      | string | No       | Phone number                       |
| role       | string | No       | `traveler` (default) or `owner`    |

**Response 201:**
```json
{
  "message": "Registration successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "role": "traveler",
    "created_at": "2024-01-01 12:00:00"
  }
}
```

**Errors:** 422 (validation), 409 (email already registered)

---

## POST /login

Authenticate user and start session.

**Authentication:** None (public)

**Request Body (JSON):**

| Parameter | Type   | Required | Description |
|-----------|--------|----------|-------------|
| email     | string | Yes      | User email  |
| password  | string | Yes      | Password    |

**Response 200:**
```json
{
  "message": "Login successful",
  "user": { "id": 1, "name": "John Doe", "email": "john@example.com", "phone": "1234567890", "role": "traveler", "created_at": "2024-01-01 12:00:00" }
}
```

**Errors:** 422 (missing fields), 401 (invalid credentials)

---

## POST /logout

Destroy session.

**Authentication:** None

**Response 200:**
```json
{ "message": "Logged out successfully" }
```

---

## GET /check_session

Check if user is authenticated.

**Authentication:** Required

**Response 200:**
```json
{
  "user": { "id": 1, "name": "John Doe", "email": "john@example.com", "phone": "1234567890", "role": "traveler", "created_at": "2024-01-01 12:00:00" }
}
```

**Errors:** 401 (not authenticated)
