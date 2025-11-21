<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use App\Models\Bathroom;
use App\Models\BathroomItemType;
use Core\Database\ActiveRecord\BelongsTo;

/**
 * @property int $id
 * @property int $bathroom_item_type_id
 * @property int $bathroom_id
 */
class BathroomItem extends Model
{
    protected static string $table = 'bathroom_items';
    protected static array $columns = [
      'bathroom_item_type_id',
      'bathroom_id',
      'quantity'
    ];

    public function validates(): void
    {
        Validations::notEmpty('bathroom_item_type_id', $this);
        Validations::isInt('bathroom_item_type_id', $this);
        Validations::isIdFrom('bathroom_item_type_id', $this, BathroomItemType::class);

        Validations::notEmpty('bathroom_id', $this);
        Validations::isInt('bathroom_id', $this);
        Validations::isIdFrom('bathroom_id', $this, Bathroom::class);

        Validations::notEmpty('quantity', $this);
        Validations::isInt('quantity', $this);
        Validations::inRange('quantity', 0, PHP_INT_MAX, $this);
    }

    /**
     * @return BelongsTo<BathroomItem, BathroomItemType>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(BathroomItemType::class, 'bathroom_item_type_id');
    }

    /**
     * @return BelongsTo<BathroomItem, Bathroom>
     */
    public function bathroom(): BelongsTo
    {
        return $this->belongsTo(Bathroom::class, 'bathroom_id');
    }
}
