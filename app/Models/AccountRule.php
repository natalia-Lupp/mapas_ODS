<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $user_rule_id
 */
class AccountRule extends Model
{
    protected static string $table = 'account_rules';
    protected static array $columns = [
      'user_id',
      'user_rule_id'
    ];

    public function validates(): void
    {
        Validations::notEmpty('user_rule_id', $this);
        Validations::isIdFrom('user_rule_id', $this, UserRule::class);

        Validations::notEmpty('user_id', $this);
        Validations::isIdFrom('user_id', $this, User::class);
    }
}
