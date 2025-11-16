<?php

namespace Database\Populate;

use App\Models\Building;

class BuildingPopulate
{
    public static function populate(): void
    {

        $numberOfResgisters = 7;

        (new Building([
          'n_floors' => 2,
          'name' => 'Bloco A'
        ]))->save();
        (new Building([
          'n_floors' => 1,
          'name' => 'Bloco B'
        ]))->save();
        (new Building([
          'n_floors' => 1,
          'name' => 'Bloco C'
        ]))->save();
        (new Building([
          'n_floors' => 1,
          'name' => 'Bloco D'
        ]))->save();
        (new Building([
          'n_floors' => 1,
          'name' => 'Bloco E'
        ]))->save();
        (new Building([
          'n_floors' => 1,
          'name' => 'Bloco F'
        ]))->save();
        (new Building([
          'n_floors' => 4,
          'name' => 'Bloco R'
        ]))->save();
        $table = Building::table();
        echo "$table populate with $numberOfResgisters\n";
    }
}
