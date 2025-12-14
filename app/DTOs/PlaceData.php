<?php

namespace App\DTOs;

final class PlaceData
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $slug = null,
        public readonly ?string $city = null,
        public readonly ?string $state = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            slug: $data['slug'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
        );
    }

    /**
     * Solo devuelve los campos presentes (ideal para update)
     */
    public function toArray(): array
    {
        return array_filter([
            'name'  => $this->name,
            'slug'  => $this->slug,
            'city'  => $this->city,
            'state' => $this->state,
        ], fn ($value) => !is_null($value));
    }
}