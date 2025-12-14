<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PlaceService;
use App\DTOs\PlaceData;
use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaceServiceTest extends TestCase
{
    use RefreshDatabase; // reinicia la DB en cada test

    public function test_create_place()
    {
        $service = $this->app->make(PlaceService::class);

        $data = new PlaceData(
            name: 'Test Place',
            slug: 'test-place',
            city: 'Asunción',
            state: 'Central'
        );

        $place = $service->create($data);

        $this->assertDatabaseHas('places', [
            'name' => 'Test Place',
            'slug' => 'test-place'
        ]);

        $this->assertInstanceOf(Place::class, $place);
    }
}