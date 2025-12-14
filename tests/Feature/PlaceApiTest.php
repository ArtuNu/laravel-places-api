<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_place_endpoint()
    {
        $response = $this->postJson('/api/places', [
            'name' => 'Test Place',
            'slug' => 'test-place',
            'city' => 'Asunción',
            'state' => 'Central'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => ['id', 'name', 'slug', 'city', 'state']
                 ]);
    }
}