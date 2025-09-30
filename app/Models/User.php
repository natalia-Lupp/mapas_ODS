<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $encrypted_password
 * @property boolean $is_active
 * @property string $last_login
 * @property string $created_at
 * @property string $updated_at
 */
class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = [
      'name',
      'email',
      'encrypted_password',
      'is_active',
      'last_login',
      'created_at',
      'updated_at'
    ];
    /** @var array<string> */
    protected array $rules;
    protected string $password = '';
    protected string $password_confirmation = '';
    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::isString('name', $this);

        Validations::notEmpty('email', $this);
        Validations::isString('email', $this);
        Validations::isEmail('email', $this);

        Validations::isPasswordStrong($this);
        if ($this->newRecord()) {
            if (Validations::passwordConfirmation($this)) {
                $this->encrypted_password  = $this->password;
            }
        }
    }

    public function validateLogin(): bool
    {
        Validations::notEmpty('email', $this);
        Validations::uniqueness('email', $this);
        Validations::isEmail('email', $this);
        return $this->isValid();
    }

    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password == null) {
            return false;
        }

        return password_verify($password, $this->encrypted_password);
    }

    public static function findByEmail(string $email): User | null
    {
        return User::findBy(['email' => $email]);
    }

    public function grant(string $rule): bool
    {
        $rule = UserRule::findByRuleType($rule);
        if (isset($rule) && $this->id) {
            $grant = new AccountRule([
            'rule_id' => $rule->id,
            'user_id' => $this->id
            ]);
            $grant->save();
            return true;
        }
        return false;
    }

    public function __set(string $property, mixed $value): void
    {
        parent::__set($property, $value);

        if (
            $property === 'encrypted_password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            parent::__set('encrypted_password', password_hash($value, PASSWORD_DEFAULT));
        }
    }

    public function hasRule(string $rule): bool
    {
        if (!isset($this->rules)) {
            $btm = $this->BelongsToMany(
                UserRule::class,
                'account_rules',
                'user_id',
                'rule_id'
            );
          /** @var UserRule[] $rules */
            $rules = $btm->get();
            $this->rules = array_map(function (UserRule $obj) {
                return $obj->rule_type;
            }, $rules);
        }
        return in_array($rule, $this->rules);
    }
}
