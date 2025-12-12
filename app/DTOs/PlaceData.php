<?php

namespace App\DTOs;

final class PlaceData
{
    public function __construct(
        public readonly string $name,
        public readonly string $slug,
        public readonly string $city,
        public readonly string $state,
    ) {}
    
    // Convert DTO to array for mass assignment / model create
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'city' => $this->city,
            'state' => $this->state,
        ];
    }
}