<?php

namespace App\Models;

use Lib\Validations;
use App\Models\IdCacheableModel;
use App\Models\BathroomItem;
use Core\Database\ActiveRecord\HasMany;

/**
 * @property int $id
 * @property int $vendor_consumption_expenditure
 * @property string $name
 */
class BathroomItemType extends IdCacheableModel
{
    protected static string $table = 'bathroom_item_types';
    protected static array $columns = [
      'vendor_consumption_expenditure',
      'name'
    ];

    public function validates(): void
    {
        Validations::notEmpty('vendor_consumption_expenditure', $this);
        Validations::isFloat('vendor_consumption_expenditure', $this);
        Validations::inRange('vendor_consumption_expenditure', 0, PHP_INT_MAX, $this);

        Validations::notEmpty('name', $this);
        Validations::isString('name', $this);
        Validations::inRangeLength('name', 1, 100, $this);
    }
    /**
     * @return HasMany<BathroomItemType, BathroomItem>
     */
    public function items(): HasMany
    {
        return $this->hasMany(BathroomItem::class, 'bathroom_item_type_id');
    }
}
