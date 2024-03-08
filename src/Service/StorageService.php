<?php

namespace App\Service;
use App\Service\Collection\FruitsCollection;
use App\Service\Collection\VegetablesCollection;
use App\Service\Collection\Item;
class StorageService
{
    public function __construct(
        private FruitsCollection $fruitsCollection,
        private VegetablesCollection $vegetablesCollection
    ){}

    public function processRequest(string $request): void
    {
        $data = json_decode($request, true);
        if (!is_array($data)) {
            // Handling JSON decoding error if needed
            return;
        }
        foreach ($data as $itemData) {
            $item = new Item($itemData['id'], $itemData['name'], $itemData['quantity'], $itemData['unit']);

            // Determine the type (fruit or vegetable) and use the specific collection
            if ($itemData['type'] === 'fruit') {
                $this->fruitsCollection->add($item);
            } elseif ($itemData['type'] === 'vegetable') {
                $this->vegetablesCollection->add($item);
            }
        }
    }

    public function getFruitsCollection(): FruitsCollection
    {
        return $this->fruitsCollection;
    }

    public function getVegetablesCollection(): VegetablesCollection
    {
        return $this->vegetablesCollection;
    }
}
