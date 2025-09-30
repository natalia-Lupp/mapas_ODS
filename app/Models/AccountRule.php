<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $rule_type
 */
class AccountRule extends Model
{
    protected static string $table = 'account_rules';
    protected static array $columns = [
      'user_id',
      'rule_id'
    ];

    public function validates(): void
    {
        Validations::notEmpty('rule_id', $this);
        Validations::isIdFrom('rule_id', $this, UserRule::class);

        Validations::notEmpty('user_id', $this);
        Validations::isIdFrom('user_id', $this, User::class);
    }
}
