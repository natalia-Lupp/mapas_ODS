<?php

namespace Database\Populate;

use App\Models\Bathroom;

class BathroomPopulate
{
    public static function populate(): void
    {

        $numberOfResgisters = 3;

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
        $table = Bathroom::table();
        echo "$table populate with $numberOfResgisters\n";
    }
}
