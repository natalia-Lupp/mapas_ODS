<?php

namespace Database\Populate;

use App\Models\BathroomItemType;

class BathroomItemTypePopulate
{
    public static function populate(): void
    {

        $numberOfResgisters = 3;

        (new BathroomItemType([
          'vendor_consumption_expenditure' => 1.5,
          'name' => 'torneira de lavado'
        ]))->save();
        (new BathroomItemType([
          'vendor_consumption_expenditure' => 5.0,
          'name' => 'Mictório'
        ]))->save();
        (new BathroomItemType([
          'vendor_consumption_expenditure' => 12.0,
          'name' => 'Vaso sanitário'
        ]))->save();
        $table = BathroomItemType::table();
        echo "$table populate with $numberOfResgisters\n";
    }
}
