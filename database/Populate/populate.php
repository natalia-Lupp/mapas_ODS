<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\UserPopulate;
use Database\Populate\UserRulePopulate;
use Database\Populate\AccountRulePopulate;

Database::create();
Database::migrate();
UserPopulate::populate();
UserRulePopulate::populate();
AccountRulePopulate::populate();
