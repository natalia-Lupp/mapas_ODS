<?php

namespace Database\Populate;

use App\Models\BathroomItemType;

class BathroomItemTypePopulate
{
    public static function populate(): void
    {


        (new BathroomItemType([
          'vendor_consumption_expenditure' => 1.5,
          'name' => 'torneira de lavado'
        ]))->save();
        (new BathroomItemType([
          'vendor_consumption_expenditure' => 12.0,
          'name' => 'Vaso sanitário'
        ]))->save();
        (new BathroomItemType([
          'vendor_consumption_expenditure' => 5.0,
          'name' => 'Mictório'
        ]))->save();
    }
}
