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

### Resetting the environment
If you want to start the project from scratch and remove all database data and volumes, run:
```bash
docker compose down -v
docker compose up -d
```

## API Endpoints
| Method | Endpoint         | Description             |
| ------ | ---------------- | ----------------------- |
| GET    | /api/places      | List all places         |
| GET    | /api/places/{id} | Retrieve a single place |
| POST   | /api/places      | Create a new place      |
| PUT    | /api/places/{id} | Update a place          |
| DELETE | /api/places/{id} | Delete a place          |

## Request & Response Examples

- For local testing and API consumption, requests should include the following headers:
Accept: application/json
Content-Type: application/json

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
      "slug": "central-park",
      "city": "New York",
      "state": "NY",
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
    "slug": "central-park",
    "city": "New York",
    "state": "NY"
  }
}
```
- Filtering Places
You can filter the list of places by name, city, or state using query parameters.

GET http://localhost:8000/api/places?name=cent
```json
{
  "success": true,
  "status": "ok",
  "code": 200,
  "message": "Place retrieved successfully",
  "data": {
    "id": 1,
    "name": "Central Park",
    "slug": "central-park",
    "city": "New York",
    "state": "NY"
  }
}
```
- Query Parameters:

| Parameter | Type   | Description                        |
| --------- | ------ | ---------------------------------- |
| `name`    | string | Filter places containing this name |
| `city`    | string | Filter places in the given city    |
| `state`   | string | Filter places in the given state   |

Multiple filters can be combined. For example:
GET /api/places?city=New+York&state=NY

### 3. POST /api/places
Request Body:
```json
{
  "name": "Golden Gate Park",
  "city": "San Francisco",
  "slug": "san-francisco",
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
    "slug": "golde-gate-park",
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
    "slug": "updated-park-name",
    "city": "San Francisco",
    "state": "CA",
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

## Running Tests

This project uses PHPUnit for automated testing.

- Run all tests:

```bash
docker compose exec app bash
php vendor/bin/phpunit
```

- Run a specific test class:

```bash
php vendor/bin/phpunit --filter PlaceServiceTest
```

## Troubleshooting

### Permission issues on storage and cache (Docker)
If you encounter errors like:
The stream or file "/var/www/storage/logs/laravel.log" could not be opened

It usually means Laravel cannot write to `storage` or `bootstrap/cache`.

#### Quick fix (inside the PHP container):

```bash
docker compose exec app bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```
### Notes:
Permissions for Laravel writable directories are already set in the Dockerfile.
When using bind mounts on some operating systems (e.g. Windows), manual permission adjustment may still be required.