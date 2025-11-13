<?php

namespace App\Models;

use App\Services\Image;
use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $bathroom_id
 * @property ?string $image_name
 */
class BathroomImage extends Model
{
    protected static string $table = 'bathroom_images';
    protected static array $columns = [
        'bathroom_id',
        'image_name'
    ];
      public ?string $image_name;
      public ?string $image_type;
      public ?int $image_size;
      public ?string $image_temp_name;
      public const int MAX_IMAGE_ACEPTED_SIZE = (10 * 1048576); // 2MB


    public function validates(): void
    {
        Validations::notEmpty('bathroom_id', $this);
        Validations::isInt('bathroom_id', $this);
        Validations::inRange('bathroom_id', 1, PHP_INT_MAX, $this);
        Validations::isIdFrom('bathroom_id', $this, Bathroom::class);

        Validations::notEmpty('image_name', $this);
        Validations::isString('image_name', $this);
        //Validations::inRangeLength('image_name', 0, $bathroom_n_floors - 1, $this);
    }

    public function imageService(): Image
    {
        return new Image($this, '/bathrooms/images');
    }

    public function bathroom(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'bathroom_id');
    }
}
