<?php

namespace Database\Populate;

use Database\Populate\UserPopulate;
use Database\Populate\UserRulePopulate;
use Database\Populate\AccountRulePopulate;
use Database\Populate\BathroomItemPopulate;
use Database\Populate\BathroomItemTypePopulate;
use Database\Populate\BathroomPopulate;
use Database\Populate\BuildingPopulate;

trait PopulateTrait
{
    public static function populate(): void
    {
      UserPopulate::populate();
      UserRulePopulate::populate();
      AccountRulePopulate::populate();
      BuildingPopulate::populate();
      BathroomPopulate::populate();
      BathroomItemTypePopulate::populate();
      BathroomItemPopulate::populate();
      ConsumptionPopulate::populate();
    }
}
