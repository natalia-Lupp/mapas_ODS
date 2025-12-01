<?php

namespace Tests\Unit\Models;

use App\Models\Bathroom;
use App\Models\Building;
use Core\Database\Database;
use Tests\TestCase;

class BathroomItemTest extends TestCase
{
    protected ?Building $building;
    public function setUp(): void
    {
        parent::setUp();
        Database::populate();
    }
    public function test_should_find_bathroom_items(): void
    {
        $bathroom = Bathroom::findById(1);
        $this->assertEquals(3, count($bathroom->items()->get()));
    }

    public function test_should_update_bathroom_items(): void
    {
        $bathroom = Bathroom::findById(1);

        $items = $bathroom->items()->get();

        $this->assertEquals(3, $items[0]->quantity);

        /**
         * @var string id
         */
        $id = "1";
        $bathroom->itemService()->update([$id => 4]);
        $items = $bathroom->items()->get();

        $this->assertEquals(4, $items[0]->quantity);
    }

    public function test_should_delete_bathroom_items_with_quantity_zero(): void
    {
        $bathroom = Bathroom::findById(1);

        $items = $bathroom->items()->get();

        $this->assertEquals(1, $items[0]->id);
        /**
         * @var string id
         */
        $id = "1";
        $bathroom->itemService()->update([$id => 0]);
        $items = $bathroom->items()->get();

        $this->assertEquals(2, $items[0]->id);
    }

    public function test_should_create_bathroom_items_when_not_exists(): void
    {
        $bathroom = Bathroom::findById(1);

        /**
         * @var string id
         */
        $id = "1";
        $bathroom->itemService()->update([$id => 0]);
        $items = $bathroom->items()->get();

        $this->assertEquals(2, $items[0]->id);
        $this->assertEquals(3, $items[$id]->id);
        $this->assertFalse(isset($items[2]));

        $bathroom->itemService()->update([$id => 10]);
        $items = $bathroom->items()->get();

        $this->assertEquals(4, $items[2]->id);
    }
}
