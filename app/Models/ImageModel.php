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

    public ?string $image_type;
    public ?int $image_size;
    public const int MAX_IMAGE_ACEPTED_SIZE = (40 * 1048576); // 2MB
    public function validates(): void
    {
        Validations::notEmpty('image_name', $this);
        Validations::isString('image_name', $this);
        //Validations::inRangeLength('image_name', 0, $bathroom_n_floors - 1, $this);
        if (isset($this->image_size) && $this->image_size > self::MAX_IMAGE_ACEPTED_SIZE) {
            $this->addError('image_name', 'A imagem excede o tamanho máximo permitido de 40 MB.');
        }

        if (isset($this->image_type) && !in_array($this->image_type, ['image/jpg', 'image/png', 'image/jpeg'])) {
            $this->addError('image_name', 'Formato de imagem inválido. Use JPG, PNG ou JPEG.');
        }
    }

    abstract public function imageService(): Image;
}
