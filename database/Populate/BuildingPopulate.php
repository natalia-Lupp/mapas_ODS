<?php

namespace Database\Populate;

use App\Models\Building;

class BuildingPopulate
{
    public static function populate(): void
    {

        $numberOfResgisters = 7;

        (new Building([
          'n_floors' => 1,
          'name' => 'bloco A'
        ]))->save();
        (new Building([
          'n_floors' => 1,
          'name' => 'bloco B'
        ]))->save();
        (new Building([
          'n_floors' => 1,
          'name' => 'bloco C'
        ]))->save();
        (new Building([
          'n_floors' => 1,
          'name' => 'bloco D'
        ]))->save();
        (new Building([
          'n_floors' => 1,
          'name' => 'bloco E'
        ]))->save();
        (new Building([
          'n_floors' => 1,
          'name' => 'bloco F'
        ]))->save();
        (new Building([
          'n_floors' => 4,
          'name' => 'bloco R'
        ]))->save();
        $table = Building::table();
        echo "$table populate with $numberOfResgisters\n";
    }
}
