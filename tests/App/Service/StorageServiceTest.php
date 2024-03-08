<?php

namespace App\Tests\App\Service;

use App\Service\Collection\FruitsCollection;
use App\Service\Collection\VegetablesCollection;
use App\Service\Collection\Item;
use App\Service\StorageService;
use PHPUnit\Framework\TestCase;

class StorageServiceTest extends TestCase
{
    /*  public function testReceivingRequest(): void
      {


          $request = file_get_contents('request.json');

          $storageService = new StorageService($request);

          $this->assertNotEmpty($storageService->getRequest());
          $this->assertIsString($storageService->getRequest());
    }*/

    public function testProcessRequestWithFruits(): void
    {
        $requestJson = '[{"id":1,"name":"Oranges","type":"fruit","quantity":10,"unit":"kg"}]';

        $fruitCollectionMock = $this->createMock(FruitsCollection::class);
        $vegetableCollectionMock = $this->createMock(VegetablesCollection::class);

        // Assertions to check if the item is added to the fruits collection only
        $fruitCollectionMock->expects($this->once())->method('add');
        $vegetableCollectionMock->expects($this->never())->method('add');

        $storageService = new StorageService($fruitCollectionMock, $vegetableCollectionMock);
        $storageService->processRequest($requestJson);

    }

    public function testProcessRequestWithVegetables(): void
    {
        $requestJson = '[{"id":2,"name":"Lettuce","type":"vegetable","quantity":170,"unit":"g"}]';

        $fruitCollectionMock = $this->createMock(FruitsCollection::class);
        $vegetableCollectionMock = $this->createMock(VegetablesCollection::class);

        // Assertions to check if the item is added to the vegetable collection only
        $vegetableCollectionMock->expects($this->once())->method('add');
        $fruitCollectionMock->expects($this->never())->method('add');

        $storageService = new StorageService($fruitCollectionMock, $vegetableCollectionMock);
        $storageService->processRequest($requestJson);

    }

    public function testProcessRequestWithKilogramsConversion(): void
    {
        $requestJson = '[{"id":3,"name":"Apples","type":"fruit","quantity":4,"unit":"kg"}]';

        $fruitCollectionMock = new FruitsCollection();
        $vegetableCollectionMock = new VegetablesCollection();

        $storageService = new StorageService($fruitCollectionMock, $vegetableCollectionMock);
        $storageService->processRequest($requestJson);

        // Assertions to check if the item is added to the fruit collection after conversion
        $fruitsCollection = $storageService->getFruitsCollection()->list();

        $actualItem = $fruitsCollection[0];
        $this->assertEquals('Bananas', $actualItem->getName());
        $this->assertEquals(2000, $actualItem->getQuantity());

        $this->assertEquals([], $storageService->getVegetablesCollection()->list());
    }

    public function testProcessRequestWithCurrentFile(): void
    {
        // Read content of the current request.json file
        $requestJson = file_get_contents('request.json');

        // Mock dependencies
        $vegetableCollectionMock = new VegetablesCollection();
        $fruitCollectionMock = new FruitsCollection();
       

        // Create an instance of StorageService
        $storageService = new StorageService($fruitCollectionMock, $vegetableCollectionMock);

        // Call the method to test
        $storageService->processRequest($requestJson);

        // Assertions to check if the items are added correctly
        $vegetablesCollection = $storageService->getVegetablesCollection()->list();
        $fruitsCollection = $storageService->getFruitsCollection()->list();
       

        // Assert that each collection has a count greater than 1
        $this->assertGreaterThan(1, count($vegetablesCollection), 'Vegetables collection should have a count greater than 1');
        $this->assertGreaterThan(1, count($fruitsCollection), 'Fruits collection should have a count greater than 1');
        
    }
}
