<?php

namespace App\Models;

use Core\Constants\Constants;
use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Lib\FileSystemHelper;
use Lib\Paginator;

/**
 * @property int $id
 * @property ?string $image_url
 * @property int $floor
 * @property int $building_id
 * @property string | null $image_name;
 * @property string | null $image_type;
 * @property int | null $image_size;
 * @property string | null $image_temp_name;
 */
class Bathroom extends Model
{
    protected static string $table = 'bathrooms';
    protected static array $columns = [
        'floor',
        'building_id'
    ];

    public function validates(): void
    {
        Validations::notEmpty('building_id', $this);
        Validations::isInt('building_id', $this);
        Validations::inRange('building_id', 1, PHP_INT_MAX, $this);
        Validations::isIdFrom('building_id', $this, Building::class);

        Validations::notEmpty('floor', $this);
        Validations::isInt('floor', $this);
        $building = Building::findById($this->building_id);
        $building_n_floors = isset($building) ? $building->n_floors : -1;
        Validations::inRange('floor', 0, $building_n_floors - 1, $this);
    }

    public function images(): HasMany
    {
        return $this->hasMany(BathroomImage::class, 'bathroom_id');
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
}
