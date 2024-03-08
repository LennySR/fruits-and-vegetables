<?php

namespace App\Tests\App\Service;

use App\Service\Collection\Collection;
use App\Service\Collection\Item;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testAddItem(): void
    {
        $collection = new Collection();

        $item = new Item(1, 'Test Item', 100, 'g');
        $collection->add($item);

        $itemList = $collection->list();

        $this->assertCount(1, $itemList);
        $this->assertEquals('Test Item', $itemList[0]->getName());
        $this->assertEquals(100, $itemList[0]->getQuantity());
    }

    public function testRemoveItem(): void
    {
        $collection = new Collection();

        $item = new Item(1, 'Test Item', 100, 'g');
        $collection->add($item);

        $this->assertCount(1, $collection->list());

        $itemId = $item->getId();

        $this->assertTrue($collection->remove($itemId));
        $this->assertCount(0, $collection->list());
    }

    public function testRemoveNonExistingItem(): void
    {
        $collection = new Collection();

        // Try to remove an item with a non-existing ID
        $this->assertFalse($collection->remove(123));
    }

    public function testListItemsInKilograms(): void
    {
        $collection = new Collection();

        $item1 = new Item(1, 'Item 1', 500, 'g');
        $item2 = new Item(2, 'Item 2', 1500, 'g');

        $collection->add($item1);
        $collection->add($item2);

        // Convert quantities to kilograms
        $itemsInKg = $collection->list('kg');

        $this->assertEquals(0.5, $itemsInKg[0]->getQuantity());
        $this->assertEquals(1.5, $itemsInKg[1]->getQuantity());
    }

    public function testSearchItem(): void
    {
        $collection = new Collection();

        $item = new Item(1, 'Test Item', 100, 'g');
        $collection->add($item);

        $result = $collection->search('Test Item');

        $this->assertInstanceOf(Item::class, $result);
        $this->assertEquals(1, $result->getId());
        $this->assertEquals('Test Item', $result->getName());
    }

    public function testSearchNonExistingItem(): void
    {
        $collection = new Collection();

        // Try to search for a non-existing item
        $result = $collection->search('Non Existing Item');

        $this->assertNull($result);
    }
}