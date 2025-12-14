<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Http\Resources\PlaceResource;
use App\Services\PlaceService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class PlaceController extends Controller
{
    use ApiResponse;

    public function __construct(private PlaceService $service) {}

    /**
     * List places, optional filter by name, city and state
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = $request->only(['name', 'city', 'state']);

        $places = $this->service->list($filters, $perPage);

        $message = $places->isEmpty()
            ? 'No places found'
            : 'Places retrieved successfully';

        return $this->success(
            PlaceResource::collection($places),
            $message,
            200
        );
    }

    /**
     * Store a new place.
     */
    public function store(StorePlaceRequest $request)
    {
        $data = $request->validated();

        try {
            $place = $this->service->create($data);
            return $this->success(new PlaceResource($place), 'Place created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create place: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Show specific place (by id)
     */
    public function show(int $id)
    {
        $place = $this->service->findById($id);

        if (!$place) {
            return $this->error('Place not found', 404);
        }

        return $this->success(new PlaceResource($place), 'Place retrieved successfully', 200);
    }

    /**
     * Update an existing place
     */
    public function update(UpdatePlaceRequest $request, int $id)
    {
        $place = $this->service->findById($id);

        if (!$place) {
            return $this->error('Place not found', 404);
        }

        $data = $request->validated();

        try {
            $updated = $this->service->update($place, $data);
            return $this->success(new PlaceResource($updated), 'Place updated successfully', 200);
        } catch (\Exception $e) {
            return $this->error('Failed to update place: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete a place
     */
    public function destroy(int $id)
    {
        $place = $this->service->findById($id);

        if (!$place) {
            return $this->error('Place not found', 404);
        }

        try {
            $this->service->delete($place);
            return $this->success(null, 'Place deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->error('Failed to delete place: ' . $e->getMessage(), 500);
        }
    }
}