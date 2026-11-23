# Trackivo REST API Documentation

Comprehensive technical reference for the Trackivo REST API powered by **Laravel Sanctum**.

---

## 1. Overview & Authentication

### 1.1. Base URL
All API endpoints are relative to the application host:
```http
http://127.0.0.1:8000/api
```
In production, replace with your configured domain (e.g., `https://trackivo.yourdomain.com/api`).

### 1.2. Authentication Scheme
Authentication is managed statelessly via **Laravel Sanctum Bearer Tokens**. Protected endpoints require the `Authorization` HTTP header:
```http
Authorization: Bearer <your_access_token>
Accept: application/json
Content-Type: application/json
```

### 1.3. Role-Based Permissions Summary
| Role | Capabilities |
| :--- | :--- |
| **Administrator** (`admin`) | Full read/write access: manage carriers, locations, shipments, tracking events, exceptions, audit logs. Allowed to delete shipments in `CREATED` or `CANCELLED` status. |
| **Logistics Operator** (`operator`) | Read master data; create and update shipments; advance state machine; log tracking checkpoints; report and resolve exceptions. |
| **Customer** (`customer`) | Read-only access restricted strictly to consignments where `customer_id == auth()->id()`. Public tracking portal access via `/track`. Cannot create or modify shipments via API. |

---

## 2. Standard Response Format

### Success Response (`200 OK` / `201 Created`)
```json
{
  "success": true,
  "message": "Operasi berhasil.",
  "data": { ... },
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 45,
    "last_page": 5
  }
}
```

### Validation Error (`422 Unprocessable Content`)
```json
{
  "message": "The transport mode field is required.",
  "errors": {
    "transport_mode": [
      "The transport mode field is required."
    ]
  }
}
```

### Unauthorized (`401 Unauthorized`)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (`403 Forbidden`)
```json
{
  "message": "Anda tidak memiliki izin untuk mengakses resource ini."
}
```

### Not Found (`404 Not Found`)
```json
{
  "message": "No query results for model [App\\Models\\Shipment] 999"
}
```

---

## 3. Authentication Endpoints

### 3.1. Register Account
Creates a new customer account and immediately issues a personal access token.

- **Endpoint:** `POST /api/register`
- **Access:** Public
- **Request Body:**
```json
{
  "name": "Alex Mercer",
  "email": "alex.mercer@example.com",
  "password": "SecurePassword123!",
  "password_confirmation": "SecurePassword123!",
  "company_name": "Apex Global Forwarding",
  "phone": "+1 415-555-8821"
}
```
- **cURL Example:**
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alex Mercer",
    "email": "alex.mercer@example.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!",
    "company_name": "Apex Global Forwarding",
    "phone": "+1 415-555-8821"
  }'
```
- **Response (`201 Created`):**
```json
{
  "success": true,
  "message": "Registrasi berhasil.",
  "data": {
    "user": {
      "id": 5,
      "name": "Alex Mercer",
      "email": "alex.mercer@example.com",
      "role": "customer",
      "company_name": "Apex Global Forwarding",
      "phone": "+1 415-555-8821",
      "created_at": "2026-11-23T14:20:00.000000Z"
    },
    "token": "1|qX8zJ9...examplePlainTextToken",
    "token_type": "Bearer"
  }
}
```

---

### 3.2. Login
Authenticates an existing user and returns an API Bearer token.

- **Endpoint:** `POST /api/login`
- **Access:** Public
- **Request Body:**
```json
{
  "email": "admin@trackivo.com",
  "password": "password"
}
```
- **cURL Example:**
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@trackivo.com",
    "password": "password"
  }'
```
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Login berhasil.",
  "data": {
    "user": {
      "id": 1,
      "name": "System Administrator",
      "email": "admin@trackivo.com",
      "role": "admin",
      "company_name": "Trackivo Global Operations",
      "phone": "+62 21 555-0100"
    },
    "token": "2|yR7aK3...plainTextToken",
    "token_type": "Bearer"
  }
}
```

---

### 3.3. Get Authenticated User Profile
Fetches the current authenticated user's record.

- **Endpoint:** `GET /api/user`
- **Access:** `auth:sanctum` (All roles)
- **cURL Example:**
```bash
curl -X GET http://127.0.0.1:8000/api/user \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN>"
```
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Data profil user berhasil diambil.",
  "data": {
    "id": 1,
    "name": "System Administrator",
    "email": "admin@trackivo.com",
    "role": "admin",
    "company_name": "Trackivo Global Operations",
    "phone": "+62 21 555-0100"
  }
}
```

---

### 3.4. Logout
Revokes the current Bearer token.

- **Endpoint:** `POST /api/logout`
- **Access:** `auth:sanctum`
- **cURL Example:**
```bash
curl -X POST http://127.0.0.1:8000/api/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN>"
```
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Logout berhasil.",
  "data": null
}
```

---

## 4. Shipments Endpoints

### 4.1. List Shipments
Returns a paginated list of shipments with multi-criteria filtering and sorting.

- **Endpoint:** `GET /api/shipments`
- **Access:** `auth:sanctum` (Customer restricted to own shipments; Admin/Operator sees all)
- **Query Parameters:**
  - `search`: Filter by tracking number or cargo type (e.g. `TRK-2026`).
  - `status`: `CREATED`, `BOOKED`, `PICKED_UP`, `IN_TRANSIT`, `DELIVERED`, `CANCELLED`.
  - `carrier_id`: Integer ID of the carrier.
  - `origin_location_id`: Integer ID of departure facility.
  - `destination_location_id`: Integer ID of arrival facility.
  - `date_from` / `date_to`: YYYY-MM-DD range on expected departure.
  - `delay_status`: `on_time`, `delayed`, `LOW`, `MEDIUM`, `HIGH`.
  - `sort`: `latest` (default), `eta_asc`, `eta_desc`, `delay_desc`, `number_asc`.
  - `per_page`: Records per page (default: 10, max: 50).
  - `page`: Page number.
- **cURL Example:**
```bash
curl -X GET "http://127.0.0.1:8000/api/shipments?status=IN_TRANSIT&per_page=5" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN>"
```
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Daftar pengiriman berhasil diambil.",
  "data": [
    {
      "id": 1,
      "tracking_number": "TRK-2026-X8K9L2",
      "transport_mode": "OCEAN",
      "cargo_type": "Precision Machine Tools",
      "weight_kg": "12500.00",
      "volume_cbm": "48.50",
      "status": "IN_TRANSIT",
      "expected_departure": "2026-11-20T08:00:00.000000Z",
      "expected_arrival": "2026-11-28T16:00:00.000000Z",
      "estimated_arrival": "2026-11-29T10:00:00.000000Z",
      "delay_hours": 18,
      "delay_severity": "MEDIUM",
      "carrier": {
        "id": 1,
        "code": "MAEU",
        "name": "Maersk Line"
      },
      "origin": {
        "id": 1,
        "code": "IDTPP",
        "name": "Port of Tanjung Priok"
      },
      "destination": {
        "id": 2,
        "code": "SGSIN",
        "name": "Port of Singapore"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 5,
    "total": 12,
    "last_page": 3
  }
}
```

---

### 4.2. Create Shipment
Creates a new consignment manifest with itemized cargo entries.

- **Endpoint:** `POST /api/shipments`
- **Access:** `auth:sanctum` (Admin & Operator only)
- **Request Body:**
```json
{
  "customer_id": 3,
  "carrier_id": 1,
  "origin_location_id": 1,
  "destination_location_id": 2,
  "transport_mode": "OCEAN",
  "cargo_type": "Semiconductor Silicon Wafers",
  "weight_kg": 4200.50,
  "expected_departure": "2026-12-01 09:00:00",
  "expected_arrival": "2026-12-08 17:00:00",
  "items": [
    {
      "item_name": "Silicon Ingot Crates",
      "quantity": 12,
      "weight_kg": 3000.00,
      "description": "Temperature-controlled container"
    },
    {
      "item_name": "Optical Calibration Rig",
      "quantity": 2,
      "weight_kg": 1200.50,
      "description": "Fragile optronics"
    }
  ]
}
```
- **cURL Example:**
```bash
curl -X POST http://127.0.0.1:8000/api/shipments \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <TOKEN>" \
  -d '{
    "customer_id": 3,
    "carrier_id": 1,
    "origin_location_id": 1,
    "destination_location_id": 2,
    "transport_mode": "OCEAN",
    "cargo_type": "Semiconductor Silicon Wafers",
    "weight_kg": 4200.50,
    "expected_departure": "2026-12-01 09:00:00",
    "expected_arrival": "2026-12-08 17:00:00",
    "items": [
      {
        "item_name": "Silicon Ingot Crates",
        "quantity": 12,
        "weight_kg": 3000.00
      }
    ]
  }'
```
- **Response (`201 Created`):**
```json
{
  "success": true,
  "message": "Pengiriman baru berhasil dibuat.",
  "data": {
    "id": 15,
    "tracking_number": "TRK-2026-K9M2V1",
    "status": "CREATED",
    "transport_mode": "OCEAN",
    "cargo_type": "Semiconductor Silicon Wafers",
    "weight_kg": "4200.50",
    "delay_hours": 0,
    "delay_severity": "NONE"
  }
}
```

---

### 4.3. Show Shipment Details
Retrieves complete shipment details including items, live location, and delay analysis.

- **Endpoint:** `GET /api/shipments/{id}`
- **Access:** `auth:sanctum` (Customer restricted to own)
- **cURL Example:**
```bash
curl -X GET http://127.0.0.1:8000/api/shipments/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN>"
```
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Detail pengiriman berhasil diambil.",
  "data": {
    "id": 1,
    "tracking_number": "TRK-2026-X8K9L2",
    "status": "IN_TRANSIT",
    "transport_mode": "OCEAN",
    "cargo_type": "Precision Machine Tools",
    "weight_kg": "12500.00",
    "volume_cbm": "48.50",
    "delay_hours": 18,
    "delay_severity": "MEDIUM",
    "current_location": {
      "name": "Singapore Strait Checkpoint",
      "latitude": 1.29027,
      "longitude": 103.851959
    },
    "delay_analysis": {
      "is_delayed": true,
      "delay_hours": 18,
      "delay_severity": "MEDIUM",
      "low_max_hours": 6,
      "high_min_hours": 24
    }
  }
}
```

---

### 4.4. Update Shipment or Transition Status
Updates metadata or transitions the state machine.

- **Endpoint:** `PUT /api/shipments/{id}` or `PATCH /api/shipments/{id}`
- **Access:** `auth:sanctum` (Admin & Operator only)
- **State Transition Request Body:**
```json
{
  "status": "DELIVERED",
  "note": "Consignment signed and accepted at final warehouse terminal."
}
```
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Pengiriman berhasil diperbarui.",
  "data": {
    "id": 1,
    "tracking_number": "TRK-2026-X8K9L2",
    "status": "DELIVERED"
  }
}
```
- **Invalid Transition Response (`422 Unprocessable Content`):**
```json
{
  "message": "Transisi status dari DELIVERED ke IN_TRANSIT tidak diizinkan oleh sistem.",
  "errors": {
    "status": [
      "Transisi status dari DELIVERED ke IN_TRANSIT tidak diizinkan oleh sistem."
    ]
  }
}
```

---

### 4.5. Delete Shipment
Removes a shipment. Only allowed if status is `CREATED` or `CANCELLED`.

- **Endpoint:** `DELETE /api/shipments/{id}`
- **Access:** `auth:sanctum` (Admin only)
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Pengiriman 'TRK-2026-K9M2V1' berhasil dihapus.",
  "data": null
}
```

---

## 5. Tracking Checkpoint Endpoints

### 5.1. Get Tracking Timeline
Retrieves chronological checkpoint milestones and latest coordinate.

- **Endpoint:** `GET /api/shipments/{id}/tracking`
- **Access:** `auth:sanctum`
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Riwayat tracking berhasil diambil.",
  "data": {
    "current_location": {
      "name": "Port of Singapore",
      "latitude": 1.2644,
      "longitude": 103.8222
    },
    "events": [
      {
        "id": 1,
        "status": "CREATED",
        "description": "Shipment order booked and manifest prepared.",
        "event_time": "2026-11-20T08:00:00.000000Z",
        "location": {
          "code": "IDTPP",
          "name": "Port of Tanjung Priok"
        }
      },
      {
        "id": 2,
        "status": "IN_TRANSIT",
        "description": "Vessel departed anchorage outbound to destination.",
        "event_time": "2026-11-21T14:30:00.000000Z",
        "location": null
      }
    ]
  }
}
```

---

### 5.2. Add Tracking Checkpoint
Appends a milestone waypoint to the consignment timeline.

- **Endpoint:** `POST /api/shipments/{id}/tracking`
- **Access:** `auth:sanctum` (Admin & Operator only)
- **Request Body:**
```json
{
  "status": "IN_TRANSIT",
  "location_id": 2,
  "latitude": 1.2644,
  "longitude": 103.8222,
  "event_time": "2026-11-22 10:00:00",
  "description": "Vessel berthed at Singapore Jurong terminal container yard."
}
```
- **Response (`201 Created`):**
```json
{
  "success": true,
  "message": "Tracking checkpoint berhasil ditambahkan.",
  "data": {
    "id": 18,
    "status": "IN_TRANSIT",
    "description": "Vessel berthed at Singapore Jurong terminal container yard.",
    "event_time": "2026-11-22T10:00:00.000000Z"
  }
}
```

---

## 6. Exceptions Endpoints

### 6.1. List Exceptions for a Shipment
- **Endpoint:** `GET /api/shipments/{id}/exceptions`
- **Access:** `auth:sanctum`
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Daftar kendala pengiriman berhasil diambil.",
  "data": [
    {
      "id": 3,
      "type": "PORT_CONGESTION",
      "severity": "MEDIUM",
      "status": "OPEN",
      "delay_impact_hours": 12,
      "description": "Berthing delay at container terminal due to quay crane maintenance.",
      "detected_at": "2026-11-22T04:00:00.000000Z"
    }
  ]
}
```

---

### 6.2. Report Exception
Logs an operational anomaly and triggers an automated recalculation of `estimated_arrival` and `delay_hours`.

- **Endpoint:** `POST /api/shipments/{id}/exceptions`
- **Access:** `auth:sanctum` (Admin & Operator only)
- **Request Body:**
```json
{
  "type": "CUSTOMS_HOLD",
  "severity": "HIGH",
  "delay_impact_hours": 36,
  "description": "Container selected for intensive physical inspection by customs authority.",
  "detected_at": "2026-11-23 08:30:00"
}
```
- **Response (`201 Created`):**
```json
{
  "success": true,
  "message": "Kendala operasional berhasil dilaporkan.",
  "data": {
    "id": 4,
    "type": "CUSTOMS_HOLD",
    "severity": "HIGH",
    "status": "OPEN",
    "delay_impact_hours": 36,
    "description": "Container selected for intensive physical inspection by customs authority."
  }
}
```

---

### 6.3. Update / Resolve Exception
Updates the lifecycle of an incident. Resolving an exception recalculates the shipment's delay and updates the timeline.

- **Endpoint:** `PATCH /api/exceptions/{id}`
- **Access:** `auth:sanctum` (Admin & Operator only)
- **Request Body:**
```json
{
  "status": "RESOLVED",
  "resolution_note": "Inspection cleared by customs. Seal verified and container released for onward loading."
}
```
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Status kendala operasional berhasil diperbarui.",
  "data": {
    "id": 4,
    "status": "RESOLVED",
    "resolution_note": "Inspection cleared by customs. Seal verified and container released for onward loading."
  }
}
```

---

## 7. Master Data Endpoints (Carriers & Locations)

### 7.1. List Carriers
- **Endpoint:** `GET /api/carriers`
- **Access:** `auth:sanctum` (All roles)
- **Query Parameters:** `search`, `is_active`, `per_page`
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Daftar carrier berhasil diambil.",
  "data": [
    {
      "id": 1,
      "code": "MAEU",
      "name": "Maersk Line",
      "contact_email": "operations@maersk.com",
      "contact_phone": "+45 33 63 33 63",
      "is_active": true
    }
  ]
}
```

---

### 7.2. Create Carrier
- **Endpoint:** `POST /api/carriers`
- **Access:** `auth:sanctum` (Admin only)
- **Request Body:**
```json
{
  "code": "HLCU",
  "name": "Hapag-Lloyd",
  "contact_email": "dispatch@hlag.com",
  "contact_phone": "+49 40 3001-0",
  "is_active": true
}
```
- **Response (`201 Created`):**
```json
{
  "success": true,
  "message": "Carrier baru berhasil ditambahkan.",
  "data": {
    "id": 5,
    "code": "HLCU",
    "name": "Hapag-Lloyd",
    "is_active": true
  }
}
```

---

### 7.3. List Locations
- **Endpoint:** `GET /api/locations`
- **Access:** `auth:sanctum` (All roles)
- **Query Parameters:** `search`, `type`, `per_page`
- **Response (`200 OK`):**
```json
{
  "success": true,
  "message": "Daftar lokasi berhasil diambil.",
  "data": [
    {
      "id": 1,
      "code": "IDTPP",
      "name": "Port of Tanjung Priok",
      "country": "Indonesia",
      "type": "PORT",
      "latitude": -6.1017,
      "longitude": 106.8833
    }
  ]
}
```

---

### 7.4. Create Location
- **Endpoint:** `POST /api/locations`
- **Access:** `auth:sanctum` (Admin only)
- **Request Body:**
```json
{
  "code": "MYPKG",
  "name": "Port Klang Container Terminal",
  "country": "Malaysia",
  "type": "PORT",
  "latitude": 2.9998,
  "longitude": 101.3928
}
```
- **Response (`201 Created`):**
```json
{
  "success": true,
  "message": "Lokasi baru berhasil ditambahkan.",
  "data": {
    "id": 12,
    "code": "MYPKG",
    "name": "Port Klang Container Terminal",
    "country": "Malaysia",
    "type": "PORT",
    "latitude": 2.9998,
    "longitude": 101.3928
  }
}
```
