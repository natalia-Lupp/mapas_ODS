<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $rule_type
 */
class UserRule extends Model
{
    protected static string $table = 'user_rules';
    protected static array $columns = [
      'rule_type'
    ];

    public function validates(): void
    {
        Validations::notEmpty('rule_type', $this);
        Validations::uniqueness('rule_type', $this);
        Validations::match('rule_type', '/^[0-9a-zA-Z]{1,12}$/', $this);
    }
    public static function findByRuleType(string $ruleType): User | null
    {
        return User::findBy(['rule_type' => $ruleType]);
    }
}
