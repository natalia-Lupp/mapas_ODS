<?php

namespace Database\Populate;

use App\Models\Bathroom;

class BathroomPopulate
{
    public static function populate(): void
    {


        (new Bathroom([
          'floor' => 0,
          'building_id' => 1
        ]))->save();
        (new Bathroom([
          'floor' => 0,
          'building_id' => 2
        ]))->save();
        (new Bathroom([
          'floor' => 0,
          'building_id' => 3
        ]))->save();
    }
}
