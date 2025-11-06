<?php

namespace Tests\Unit\Models;

use App\Models\Bathroom;
use App\Models\Building;
use Tests\TestCase;

class BuildingTest extends TestCase
{
    public function test_should_create_new_building(): void
    {
        $this->assertEquals(0, count(Building::all()));
        (new Building([
            'name' => 'H',
            'n_floors' => 3
        ]))->save();
        $this->assertEquals(1, count(Building::all()));
    }

    public function test_should_has_a_validy_floor_number(): void
    {
        $this->assertEquals(0, count(Building::all()));
        $building = new Building([
            'name' => 'H',
            'n_floors' => -3
        ]);
        $building->save();
        $this->assertEquals(
            "n_floors deve ser maior ou igual à 1!",
            $building->errors('n_floors')
        );
        $this->assertEquals(0, count(Building::all()));
    }

    public function test_should_has_a_validy_name(): void
    {
        $this->assertEquals(0, count(Building::all()));
        $building = new Building([
            'name' => '',
            'n_floors' => 3
        ]);
        $building->save();
        $this->assertEquals(
            "Nome do prédio não pode ser vazio",
            $building->errors('name')
        );
        $this->assertEquals(0, count(Building::all()));
    }
    public function test_should_create_a_bathroom_paginator(): void
    {
        $building = new Building([
            'name' => 'H',
            'n_floors' => 3
        ]);
        $building->save();

        $this->assertEquals(
            0,
            count(
                $building->getBathroomPaginator(1, 10, 'route')->registers()
            )
        );

        $bathroom = new Bathroom([
            'building_id' => 1,
            'floor' => 0
        ]);
        $bathroom->save();

        $this->assertEquals(
            1,
            count(
                $building->getBathroomPaginator(1, 10, 'route')->registers()
            )
        );
    }
    public function test_building_cache(): void
    {
        (new Building([
            'name' => 'H',
            'n_floors' => 3
        ]))->save();
        $building1 = Building::findById(1);
        $building2 = Building::findById(1);
        $this->assertEquals(spl_object_id($building1), spl_object_id($building2));
    }
}
