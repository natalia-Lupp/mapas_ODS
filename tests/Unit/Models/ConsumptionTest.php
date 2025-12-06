<?php

namespace Tests\Unit\Models;

use App\Models\Bathroom;
use App\Models\BathroomItem;
use App\Models\Consumption;
use App\Models\Building;
use App\Models\User;
use Core\Database\Database;
use Tests\TestCase;

class ConsumptionTest extends TestCase
{
    protected ?Building $building;
    public function setUp(): void
    {
        parent::setUp();
        Database::populate();
    }
    public function test_should_create_new_consumption(): void
    {
        $count =  count(Consumption::all());
        $consumption = new Consumption([
          'bathroom_id' => 1,
          'user_id' => 1,
          'bathroom_item_id' => 1,
          'quantity' => 350.0,
          'date' => '2025-12-05'
        ]);
        $consumption->save();
        $this->assertEquals($count + 1, count(Consumption::all()));
    }

    public function test_should_has_a_positive_quantity(): void
    {
        $count =  count(Consumption::all());
        $consumption = new Consumption([
          'bathroom_id' => 1,
          'user_id' => 1,
          'bathroom_item_id' => 1,
          'quantity' => -350.0,
          'date' => '2025-12-05'
        ]);
        $consumption->save();
        $this->assertEquals(
            'quantity deve ser maior ou igual à 0!',
            $consumption->errors('quantity')
        );
        $this->assertEquals($count, count(Consumption::all()));
    }

    public function test_should_has_a_validy_date(): void
    {
        $count =  count(Consumption::all());
        $consumption = new Consumption([
          'bathroom_id' => 1,
          'user_id' => 1,
          'bathroom_item_id' => 1,
          'quantity' => 350.0,
          'date' => '2025-12-5'
        ]);
        $consumption->save();
        $this->assertEquals(
            'date deve ser uma data!',
            $consumption->errors('date')
        );
        $this->assertEquals($count, count(Consumption::all()));
    }

    public function test_date_should_be_range(): void
    {
        $count =  count(Consumption::all());
        $curentDate =  date('Y-m-d');

        $consumption = new Consumption([
          'bathroom_id' => 1,
          'user_id' => 1,
          'bathroom_item_id' => 1,
          'quantity' => 350.0,
          'date' => date('Y-m-d', strtotime('+3 days'))
        ]);
        $consumption->save();
        $this->assertEquals(
            "date deve ser anterior a $curentDate!",
            $consumption->errors('date')
        );

        $consumption = new Consumption([
          'bathroom_id' => 1,
          'user_id' => 1,
          'bathroom_item_id' => 1,
          'quantity' => 350.0,
          'date' => '1963-07-09'
        ]);
        $consumption->save();
        $this->assertEquals(
            'date deve ser posterior a 1970-01-01!',
            $consumption->errors('date')
        );

        $this->assertEquals($count, count(Consumption::all()));
    }

    public function test_should_has_a_validy_user_id(): void
    {

        $count =  count(Consumption::all());
        $consumption = new Consumption([
          'bathroom_id' => 1,
          'user_id' => 10,
          'bathroom_item_id' => 1,
          'quantity' => 350.0,
          'date' => '2025-12-05'
        ]);
        $consumption->save();
        $this->assertEquals(
            "user_id deve fazer referência a um registro valido.",
            $consumption->errors('user_id')
        );
        $this->assertEquals($count, count(Consumption::all()));
    }

    public function test_should_has_a_validy_bathroom_id(): void
    {
        $count =  count(Consumption::all());
        $consumption = new Consumption([
          'bathroom_id' => 100,
          'user_id' => 1,
          'bathroom_item_id' => 1,
          'quantity' => 350.0,
          'date' => '2025-12-5'
        ]);
        $consumption->save();
        $this->assertEquals(
            "bathroom_id deve fazer referência a um registro valido.",
            $consumption->errors('bathroom_id')
        );
        $this->assertEquals($count, count(Consumption::all()));
    }

    public function test_should_has_a_validy_bathroom_item_id(): void
    {
        $count =  count(Consumption::all());
        $consumption = new Consumption([
          'bathroom_id' => 1,
          'user_id' => 1,
          'bathroom_item_id' => 100,
          'quantity' => 350.0,
          'date' => '2025-12-5'
        ]);
        $consumption->save();
        $this->assertEquals(
            "bathroom_item_id deve fazer referência a um registro valido.",
            $consumption->errors('bathroom_item_id')
        );
        $this->assertEquals($count, count(Consumption::all()));
    }

    public function test_should_find_bathroom(): void
    {
      $consumption = Consumption::findById(1);
      $obj = $consumption->bathroom()->get();
      $this->assertNotNull($obj);
      $this->assertEquals(1, $obj->id);
      $this->assertEquals(get_class($obj), Bathroom::class);
    }

    public function test_should_find_item(): void
    {
      $consumption = Consumption::findById(1);
      $obj = $consumption->bathroomItem()->get();
      $this->assertNotNull($obj);
      $this->assertEquals(1, $obj->id);
      $this->assertEquals(get_class($obj), BathroomItem::class);
    }

    public function test_should_find_user(): void
    {
      $consumption = Consumption::findById(1);
      $obj = $consumption->user()->get();
      $this->assertNotNull($obj);
      $this->assertEquals(1, $obj->id);
      $this->assertEquals(get_class($obj), User::class);
    }
}
