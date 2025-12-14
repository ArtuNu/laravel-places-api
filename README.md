# Laravel Places API with Docker

A **Laravel REST API** for managing a CRUD of places (`name`, `slug`, `city`, `state`) with PostgreSQL, fully Dockerized for development and testing.

---

## 🛠 Requirements

- Docker
- Docker Compose
- PostgreSQL 16+
- Postman or any HTTP client (optional for testing)

---

## Installation

### 1. Clone the repository
```bash
git clone https://github.com/your-username/laravel-api.git
cd laravel-api
```

### 2. Copy the environment file
```bash
cp .env.example .env
```

### 3. Start Docker containers
```bash
docker compose up -d --build
```

### 4. Install Laravel dependencies
```bash
docker compose exec app composer install
```

### 5. Generate Laravel app key
```bash
docker compose exec app php artisan key:generate
```

### 6. Run database migrations
```bash
docker compose exec app php artisan migrate
```

## Access
API: http://localhost:8000

PostgreSQL: localhost:5432 (user: laravel, password: secret)

## Useful Docker Commands
docker compose down                      # Stop containers
docker compose exec app php artisan      # Run Artisan commands
docker compose logs -f                   # View logs
docker compose ps                        

## API Endpoints
| Method | Endpoint         | Description             |
| ------ | ---------------- | ----------------------- |
| GET    | /api/places      | List all places         |
| GET    | /api/places/{id} | Retrieve a single place |
| POST   | /api/places      | Create a new place      |
| PUT    | /api/places/{id} | Update a place          |
| DELETE | /api/places/{id} | Delete a place          |

## Request & Response Examples

### 1. GET /api/places
GET http://localhost:8000/api/places
```json
{
  "success": true,
  "status": "ok",
  "code": 200,
  "message": "Places retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Central Park",
      "city": "New York",
      "state": "NY",
      "created_at": "2025-12-13T17:40:33.000000Z",
      "updated_at": "2025-12-13T17:40:33.000000Z"
    }
  ]
}
```
Response (empty result):
```json
{
  "success": true,
  "status": "ok",
  "code": 200,
  "message": "No places found",
  "data": []
}
```

### 2. GET /api/places/{id}
GET http://localhost:8000/api/places/1
```json
{
  "success": true,
  "status": "ok",
  "code": 200,
  "message": "Place retrieved successfully",
  "data": {
    "id": 1,
    "name": "Central Park",
    "city": "New York",
    "state": "NY"
  }
}
```

### 3. POST /api/places
Request Body:
```json
{
  "name": "Golden Gate Park",
  "city": "San Francisco",
  "state": "CA"
}
```
Response:
```json
{
  "success": true,
  "status": "ok",
  "code": 201,
  "message": "Place created successfully",
  "data": {
    "id": 2,
    "name": "Golden Gate Park",
    "city": "San Francisco",
    "state": "CA",
  }
}
```
### 4. PUT /api/places/{id}
Request Body:
```json
{
  "name": "Updated Park Name"
}
```
Response:
```json
{
  "success": true,
  "status": "ok",
  "code": 200,
  "message": "Place updated successfully",
  "data": {
    "id": 2,
    "name": "Updated Park Name",
    "city": "San Francisco",
    "state": "CA",
    "created_at": "2025-12-13T18:00:00.000000Z",
    "updated_at": "2025-12-13T18:10:00.000000Z"
  }
}
```
### 5. DELETE /api/places/{id}
DELETE http://localhost:8000/api/places/2
Response:
```json
{
  "success": true,
  "status": "ok",
  "code": 200,
  "message": "Place deleted successfully",
  "data": null
}
```
Note: Using HTTP 204 No Content will return an empty body. Using 200 allows sending a message.
## Notes
The API uses Route Model Binding, so requests for non-existent resources automatically return 404.
All responses follow this standardized JSON structure:
```json
{
  "success": true,
  "status": "ok",
  "code": 200,
  "message": "Operation successful",
  "data": { ... }
}
```
### Filters can be applied on /api/places using query parameters:
GET /api/places?name=Park&city=New York&state=NY
### To reset the database:
docker compose exec app php artisan migrate:fresh
### Test the API easily with Postman or any HTTP client.
