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
use Illuminate\Http\Response;
use App\Traits\ApiResponse;

class PlaceController extends Controller
{
    use ApiResponse;

    public function __construct(private PlaceService $service) {}

    /**
     * List places, optional filter by name via ?name=<name>
     */
    public function index(Request $request)
    {
        $places = Place::query()
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->name . '%');
            })
            ->when($request->filled('city'), function ($q) use ($request) {
                $q->where('city', 'ilike', '%' . $request->city . '%');
            })
            ->when($request->filled('state'), function ($q) use ($request) {
                $q->where('state', 'ilike', '%' . $request->state . '%');
            })
            ->get();

        return response()->json([
            'success' => true,
            'status'  => 'ok',
            'code'    => 200,
            'data'    => $places,
        ]);
    }

    /**
     * Store a new place.
     */
    public function store(StorePlaceRequest $request)
    {
        // Build slug if client didn't provide
        $payload = $request->validated();
        if (empty($payload['slug'])) {
            $payload['slug'] = $this->service->generateSlug($payload['name']);
        }

        $dto = new PlaceData(
            name: $payload['name'],
            slug: $payload['slug'],
            city: $payload['city'],
            state: $payload['state']
        );

        try {
            $place = $this->service->create($dto);
            return $this->success(new PlaceResource($place), 'Place created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create place: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Show specific place (by id)
     */
    public function show(Place $place)
    {
        if (!$place) {
            return $this->error('Place not found', 404);
        }

        return $this->success(new PlaceResource($place), 'Place retrieved successfully', 200);
    }

    /**
     * Update an existing place
     */
    public function update(UpdatePlaceRequest $request, Place $place)
    {
        if (!$place) {
            return $this->error('Place not found', 404);
        }

        $payload = $request->validated();

        if (empty($payload['slug'])) {
            $payload['slug'] = $this->service->generateSlug($payload['name']);
        }

        $dto = new PlaceData(
            name: $payload['name'],
            slug: $payload['slug'],
            city: $payload['city'],
            state: $payload['state']
        );

        try {
            $updated = $this->service->update($place, $dto);
            return $this->success(new PlaceResource($updated), 'Place updated successfully', 200);
        } catch (\Exception $e) {
            return $this->error('Failed to update place: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete a place
     */
    public function destroy(Place $place)
    {
        if (!$place) {
            return $this->error('Place not found', 404);
        }

        try {
            $this->service->delete($place);
            return $this->success(null, 'Place deleted successfully', 204);
        } catch (\Exception $e) {
            return $this->error('Failed to delete place: ' . $e->getMessage(), 500);
        }
    }
}