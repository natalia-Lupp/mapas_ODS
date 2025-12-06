<?php

namespace Database\Populate;

use App\Models\Consumption;

class ConsumptionPopulate
{
    public static function populate(): void
    {


        (new Consumption([
          'bathroom_id' => 1,
          'user_id' => 1,
          'bathroom_item_id' => 1,
          'quantity' => 350.0,
          'date' => '2025-11-05'
        ]))->save();
        (new Consumption([
          'bathroom_id' => 1,
          'user_id' => 1,
          'bathroom_item_id' => 2,
          'quantity' => 250.0,
          'date' => '2025-11-05'
        ]))->save();
        //(new Consumption([
        //  'bathroom_id' => 1,
        //  'user_id' => 1,
        //  'quantity' => 0.0,
        //  'bathroom_item_id' => 3,
        //  'date' => ''
        //]))->save();
    }
}
