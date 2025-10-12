<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $vendor_consumption_expenditure
 * @property string $name
 */
class BathroomItemType extends Model
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
}
