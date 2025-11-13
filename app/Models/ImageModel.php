<?php

namespace App\Models;

use App\Services\Image;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property ?string $image_name
 */
abstract class ImageModel extends Model
{
    protected const IMAGE_FILD_NAME = 'image_name';

    public function validates(): void
    {
        Validations::notEmpty('image_name', $this);
        Validations::isString('image_name', $this);
        //Validations::inRangeLength('image_name', 0, $bathroom_n_floors - 1, $this);
    }

    abstract public function imageService(): Image;
}
