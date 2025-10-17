<?php

require __DIR__ . '/../../config/bootstrap.php';

use App\Models\Bathroom;
use Core\Database\Database;
use Database\Populate\UserPopulate;
use Database\Populate\UserRulePopulate;
use Database\Populate\AccountRulePopulate;
use Database\Populate\BathroomItemTypePopulate;
use Database\Populate\BuildingPopulate;

Database::create();
Database::migrate();
UserPopulate::populate();
UserRulePopulate::populate();
AccountRulePopulate::populate();
BuildingPopulate::populate();
BathroomPopulate::populate();
BathroomItemTypePopulate::populate();
