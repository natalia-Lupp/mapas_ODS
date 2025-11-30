<?php

namespace Database\Populate;

use App\Models\Building;

class BuildingPopulate
{
    public static function populate(): void
    {


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
    }
}
