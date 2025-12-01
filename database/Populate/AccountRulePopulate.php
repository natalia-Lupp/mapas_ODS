<?php
namespace Database\Populate;

use App\Models\AccountRule;

class AccountRulePopulate
{
    public static function populate(): void
    {


        $admin = new AccountRule([
          'user_rule_id' => 3,
          'user_id' => 1
        ]);
        $admin->save();
        $admin = new AccountRule([
          'user_rule_id' => 1,
          'user_id' => 1
        ]);
        $admin->save();

        $employer = new AccountRule([
          'user_rule_id' => 2,
          'user_id' => 2
        ]);
        $employer->save();

        $client = new AccountRule([
          'user_rule_id' => 1,
          'user_id' => 3
        ]);
        $client->save();

    }
}

