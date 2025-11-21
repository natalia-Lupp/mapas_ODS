<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\Model;
use App\Models\User;
use App\Models\Bathroom;
use App\Models\BathroomItem;
use Lib\Validations;

/**
 * @property ?int $user_id
 * @property ?int $quantity
 * @property ?int $bathroom_id
 * @property ?int $bathroom_item_id
 * @property ?string $date
 */
class Consumption extends Model
{
    protected static string $table = 'consumptions';
    /** @var array<int, string> */
    protected static array $columns = [
      `user_id`,
      `quantity`,
      `bathroom_id`,
      `bathroom_item_id`,
      `date`
    ];

    public function validates(): void
    {
        Validations::notEmpty('user_id', $this);
        Validations::isInt('user_id', $this);
        Validations::isIdFrom('user_id', $this, User::class);


        Validations::notEmpty('bathroom_id', $this);
        Validations::isInt('bathroom_id', $this);
        Validations::isIdFrom('bathroom_id', $this, Bathroom::class);

        Validations::notEmpty('bathroom_item_id', $this);
        Validations::isInt('bathroom_item_id', $this);
        Validations::isIdFrom('bathroom_item_id', $this, BathroomItem::class);

        Validations::notEmpty('date', $this);
        Validations::isString('date', $this);
        Validations::isDate('date', $this);

        Validations::notEmpty('quantiry', $this);
        Validations::isInt('quantiry', $this);
        Validations::inRange('quantiry', 1, PHP_INT_MAX, $this);
    }

    /**
     * @return BelongsTo<Consumption, Bathroom>
     */
    public function bathroom(): BelongsTo
    {
        return $this->belongsTo(Bathroom::class, 'bathroom_id');
    }

    /**
     * @return BelongsTo<Consumption, BathroomItem>
     */
    public function bathroomItem(): BelongsTo
    {
        return $this->belongsTo(BathroomItem::class, 'bathroom_item_id');
    }

    /**
     * @return BelongsTo<Consumption, User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
