<?php

namespace Tests\Unit\Models;

use App\Models\UserRule;
use Tests\TestCase;

class UserRuleTest extends TestCase
{
    private UserRule $user_rule;
    private const string TEST_RULE = 'client';

    public function setUp(): void
    {
        parent::setUp();

        $this->user_rule = new UserRule(['rule_type' => self::TEST_RULE]);
        $this->user_rule->save();
    }

    public function test_should_perciste(): void
    {
        $this->assertNotNull(UserRule::findById(1));
    }

    public function test_should_not_perciste_if_invalid_rule_type(): void
    {
        $this->user_rule = new UserRule(['rule_type' => 'special_client']);
        $this->user_rule->save();
        $this->assertEquals(
            $this->user_rule->errors('rule_type'),
            "Don't math the patern /^[0-9a-zA-Z]{1,12}$/"
        );
        $this->assertNotNull(UserRule::findById(1));
    }

    public function test_should_not_perciste_if_null_rule_type(): void
    {
        $this->user_rule = new UserRule([]);
        $this->user_rule->save();
        $this->assertNotNull(UserRule::findById(1));
    }
}
