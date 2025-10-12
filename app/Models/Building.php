<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Lib\Paginator;

/**
 * @property int $id
 * @property int $n_floors
 * @property string $name
 */
class Building extends Model
{
    protected static string $table = 'buildings';
    protected static array $columns = [
      'n_floors',
      'name'
    ];

    public function validates(): void
    {
        Validations::notEmpty('n_floors', $this);
        Validations::isInt('n_floors', $this);
        Validations::inRange('n_floors', 1, PHP_INT_MAX, $this);

        Validations::notEmpty('name', $this);
        Validations::isString('name', $this);
        Validations::inRangeLength('name', 1, 100, $this);
    }

    public function getBathroomPaginator(int $page, int $per_page, ?string $route): Paginator
    {
        return Bathroom::paginate(
            $page,
            $per_page,
            $route,
            ['building_id' => $this->id]
        );
    }
}
