<?php

namespace App\Models;

use Core\Constants\Constants;
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
        'image_url',
        'floor',
        'building_id'
    ];
    public ?string $image_name;
    public ?string $image_type;
    public ?int $image_size;
    public ?string $image_temp_name;
    public const int MAX_IMAGE_ACEPTED_SIZE = (2 * 1048576); // 2MB

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


        if (isset($this->image_url)) {
            Validations::inRangeLength('image_url', 10, 255, $this);
        } else {
            $this->image_url = null;
        }
        if (isset($this->image_name) && $this->image_name !== '') {
            Validations::match('image_name', '/^.*\.(jpeg|jpg|png)$/', $this);
            Validations::inRange('image_size', 1, self::MAX_IMAGE_ACEPTED_SIZE, $this);
            Validations::isString('image_type', $this);
            Validations::inEnum('image_type', [
                'image/png',
                'image/jpeg'
            ], $this);
        } else {
            $this->image_name = '';
        }
        if (isset($this->image_url)) {
            Validations::inRangeLength('image_url', 10, 255, $this);
        } else {
            $this->image_url = null;
        }
    }

    public function save(): bool
    {
        if (isset($this->image_name) && $this->image_name !== '' && $this->isValid()) {
            if (isset($this->image_url)) {
                unlink(Constants::rootPath()->join('public/assets/uploads/' . $this->image_url));
            }
            $tokens = explode('.', $this->image_name);
            $this->image_url = md5(uniqid()) . '.' . array_pop($tokens);
            FileSystemHelper::move(
                $this->image_temp_name,
                Constants::rootPath()->join('public/assets/uploads/' . $this->image_url)
            );
        }
        return parent::save();
    }

    public function getPaginateByBuildingId(
        int $building_id,
        int $page,
        int $per_page,
        ?string $route
    ): Paginator {
        return Bathroom::paginate(
            $page,
            $per_page,
            $route,
            ['building_id' => $building_id]
        );
    }
    public function update(array $data): bool
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
        return $this->save();
    }
}
