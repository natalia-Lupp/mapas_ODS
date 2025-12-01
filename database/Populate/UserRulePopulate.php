<?php

namespace Database\Populate;

use App\Models\UserRule;

class UserRulePopulate
{
    public static function populate(): void
    {


        $client = new UserRule(['rule_type' => 'client']);
        $client->save();
        $admin = new UserRule(['rule_type' => 'employer']);
        $admin->save();
        $employer = new UserRule(['rule_type' => 'admin']);
        $employer->save();
    }
}

