<?php

namespace Database\Populate;

use App\Models\UserRule;

class UserRulePopulate
{
    public static function populate(): void
    {

        $numberOfResgisters = 3;

        $client = new UserRule(['rule_type' => 'client']);
        $client->save();
        $admin = new UserRule(['rule_type' => 'employer']);
        $admin->save();
        $employer = new UserRule(['rule_type' => 'admin']);
        $employer->save();
        echo "user rules populate with $numberOfResgisters\n";
    }
}

