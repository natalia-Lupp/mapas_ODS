<?php

namespace Tests\Unit\Models;

use App\Models\Bathroom;
use App\Models\Building;
use Tests\TestCase;

class BathroomTest extends TestCase
{
    protected ?Building $building;
    public function setUp(): void
    {
        parent::setUp();
        $this->building = new Building([
        'name' => 'H',
        'n_floors' => 3
        ]);
        $this->building->save();
    }
    public function test_should_create_new_bathroom(): void
    {
        $this->assertEquals(0, count(Bathroom::all()));
        $bathroom = new Bathroom([
        'building_id' => 1,
        'floor' => 0
        ]);
        $bathroom->save();
        $this->assertEquals(1, count(Bathroom::all()));
    }

    public function test_should_has_a_validy_floor_number(): void
    {
        $this->assertEquals(0, count(Bathroom::all()));
        $bathroom = new Bathroom([
        'building_id' => 1,
        'floor' => 3
        ]);
        $bathroom->save();
        $this->assertEquals(
            "floor deve ser menor ou igual à 2!",
            $bathroom->errors('floor')
        );
        $this->assertEquals(0, count(Bathroom::all()));
    }

    public function test_should_has_a_validy_building_id(): void
    {
        $this->assertEquals(0, count(Bathroom::all()));
        $bathroom = new Bathroom([
        'building_id' => 2,
        'floor' => 2
        ]);
        $bathroom->save();
        $this->assertEquals(
            "building_id deve fazer referência a um registro valido.",
            $bathroom->errors('building_id')
        );
        $this->assertEquals(0, count(Bathroom::all()));
    }
}
