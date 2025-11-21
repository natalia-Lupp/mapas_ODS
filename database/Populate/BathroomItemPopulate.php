<?php

namespace Database\Populate;

use App\Models\BathroomItem;

class BathroomItemPopulate
{
    public static function populate(): void
    {

        $numberOfResgisters = 3;

        (new BathroomItem([
          'bathroom_item_type_id' => 1,
          'bathroom_id' => 1,
          'quantity' => 3
        ]))->save();
        (new BathroomItem([
          'bathroom_item_type_id' => 2,
          'bathroom_id' => 1,
          'quantity' => 3
        ]))->save();
        (new BathroomItem([
          'bathroom_item_type_id' => 3,
          'bathroom_id' => 1,
          'quantity' => 0
        ]))->save();
        $table = BathroomItem::table();
        echo "$table populate with $numberOfResgisters\n";
    }
}
