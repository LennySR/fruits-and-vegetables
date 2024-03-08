<?php

namespace App\Service\Collection;

class Collection
{
    protected array $items = [];

    public function add(Item $item): void
    {
        $this->items[] = $item;
        //TODO: possible changes based on duplicate id of item
    }

    public function remove(int $itemId): bool
    {
        foreach ($this->items as $key => $item) {
            if ($item->getId() === $itemId) {
                unset($this->items[$key]);
                return true; // Item found and removed
            }
        }

        return false; // Item not found
    }

    public function list(?string $unit = null): array
    {
        $filteredItems = $this->items;

        if ($unit === 'kg') {
            // Convert quantity to kilograms if the unit is 'kg'
            $filteredItems = array_map(
                static function (Item $item) {
                    $item->setQuantity($item->getQuantity() / 1000);
                    return $item;
                },
                $filteredItems
            );
        }

        return $filteredItems;
    }

    public function search(string $name): ?Item
    {
        foreach ($this->items as $item) {
            if ($item->getName() === $name) {
                return $item;
            }
        }

        return null;
    }
}