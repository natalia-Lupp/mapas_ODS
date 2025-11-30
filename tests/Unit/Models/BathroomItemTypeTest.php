<?php

namespace Tests\Unit\Models;

use App\Models\Bathroom;
use App\Models\BathroomItem;
use App\Models\BathroomItemType;
use Database\Populate\BathroomItemPopulate;
use Database\Populate\BathroomItemTypePopulate;
use Database\Populate\BathroomPopulate;
use Database\Populate\BuildingPopulate;
use Tests\TestCase;

class BathroomItemTypeTest extends TestCase
{
    public function test_should_create_new_bathroomItemType(): void
    {
        $this->assertEquals(0, count(BathroomItemType::all()));
        (new BathroomItemType([
        'vendor_consumption_expenditure' => 10.0,
        'name' => 'torneira de lavado'
        ]))->save();
        $this->assertEquals(1, count(BathroomItemType::all()));
    }

    public function test_should_has_a_validy_vendor_consumption_expenditure(): void
    {
        $this->assertEquals(0, count(BathroomItemType::all()));
        $bathroomItemType = new BathroomItemType([
        'name' => 'torneira de lavado',
        'vendor_consumption_expenditure' => -3
        ]);
        $bathroomItemType->save();
        $this->assertEquals(
            "vendor_consumption_expenditure deve ser maior ou igual à 0!",
            $bathroomItemType->errors('vendor_consumption_expenditure')
        );
        $this->assertEquals(0, count(BathroomItemType::all()));
    }

    public function test_should_has_a_validy_name(): void
    {
        $this->assertEquals(0, count(BathroomItemType::all()));
        $bathroomItemType = new BathroomItemType([
        'name' => '',
        'vendor_consumption_expenditure' => 3.0
        ]);
        $bathroomItemType->save();
        $this->assertEquals(
            "Deve ser maior ou igual à 1 caracteres!",
            $bathroomItemType->errors('name')
        );
        $this->assertEquals(0, count(BathroomItemType::all()));
    }

    public function test_should_find_all_associated_items(): void
    {
      BathroomItemTypePopulate::populate();
      BuildingPopulate::populate();
      BathroomPopulate::populate();
      BathroomItemPopulate::populate();
      $type = BathroomItemType::findById(1);
      $items = $type->items()->get();
      $this->assertEquals(1, count($items));
      $this->assertTrue($items[0] instanceof BathroomItem);
      $this->assertEquals($type->id, $items[0]->bathroom_item_type_id);
    }
    public function test_should_find_all_associated_bathroms(): void
    {
      BathroomItemTypePopulate::populate();
      BuildingPopulate::populate();
      BathroomPopulate::populate();
      BathroomItemPopulate::populate();
      $type = BathroomItemType::findById(1);
      $bathrooms = $type->bathrooms()->get();
      $this->assertEquals(1, count($bathrooms));
      $this->assertTrue($bathrooms[0] instanceof Bathroom);

    }
}
