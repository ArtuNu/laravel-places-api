<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Http\Resources\PlaceResource;
use App\DTOs\PlaceData;
use App\Models\Place;
use App\Services\PlaceService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class PlaceController extends Controller
{
    use ApiResponse;

    protected PlaceService $service;

    public function __construct(PlaceService $service)
    {
        $this->service = $service;
    }

    /**
     * List places, optional filter by name, city and state
     */
    public function index(Request $request)
    {
        $query = Place::query();

        if ($request->filled('name')) {
            $query->where('name', 'ilike', '%' . $request->name . '%');
        }
        if ($request->filled('city')) {
            $query->where('city', 'ilike', '%' . $request->city . '%');
        }
        if ($request->filled('state')) {
            $query->where('state', 'ilike', '%' . $request->state . '%');
        }

        $places = $query->get();

        $message = $places->isEmpty() ? 'No places found' : 'Places retrieved successfully';

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
        try {
            $placeData = PlaceData::fromArray($request->validated());

            $place = $this->service->create($placeData);

            return $this->success(
                new PlaceResource($place),
                'Place created successfully',
                201
            );
        } catch (\Throwable $e) {
            return $this->error(
                'Failed to create place: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Show specific place (by id)
     */
    public function show(Place $place)
    {
        return $this->success(
            new PlaceResource($place),
            'Place retrieved successfully',
            200
        );
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

        try {
            $placeData = PlaceData::fromArray($request->validated());

            $updated = $this->service->update($place, $placeData);

            return $this->success(
                new PlaceResource($updated),
                'Place updated successfully',
                200
            );
        } catch (\Throwable $e) {
            return $this->error(
                'Failed to update place: ' . $e->getMessage(),
                500
            );
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