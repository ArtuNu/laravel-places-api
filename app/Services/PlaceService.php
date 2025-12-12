<?php

namespace App\Services;

use App\Models\Place;
use App\DTOs\PlaceData;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PlaceService
{
    /**
     * List places with optional name filter and pagination.
     */
    public function list(?string $name = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Place::query();

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Create a place from PlaceData DTO within a transaction.
     */
    public function create(PlaceData $data): Place
    {
        return DB::transaction(function () use ($data) {
            return Place::create($data->toArray());
        });
    }

    /**
     * Update place with PlaceData DTO.
     */
    public function update(Place $place, PlaceData $data): Place
    {
        return DB::transaction(function () use ($place, $data) {
            $place->update($data->toArray());
            return $place->fresh();
        });
    }

    /**
     * Get single place
     */
    public function findById(int $id): ?Place
    {
        return Place::find($id);
    }

    /**
     * Optionally delete (not required by your spec but included).
     */
    public function delete(Place $place): void
    {
        $place->delete();
    }

    /**
     * Helper: generate slug from name if not provided
     */
    public function generateSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Place::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }
}