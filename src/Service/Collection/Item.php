<?php

namespace App\Service\Collection;


class Item implements \JsonSerializable
{
    private int $id;
    private string $name;
    private float $quantity;

    public function __construct(int $id, string $name, float $quantity, string $unit)
    {
        $this->id = $id;
        $this->name = $name;

        // Convert quantity to grams if the unit is in kilograms
        $this->quantity = ($unit === 'kg') ? $quantity * 1000 : $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->quantity,
        ];
    }

}