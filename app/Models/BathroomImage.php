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
class BathroomImage extends ImageModel
{
    protected static string $table = 'bathroom_images';
    protected static array $columns = [
        'bathroom_id',
        self::IMAGE_FILD_NAME
    ];
    public ?string $image_name;
    public ?string $image_type;
    public ?int $image_size;
    public ?string $image_temp_name;
    public const int MAX_IMAGE_ACEPTED_SIZE = (40 * 1048576); // 2MB


    public function validates(): void
    {
        parent::validates();
        Validations::notEmpty('bathroom_id', $this);
        Validations::isInt('bathroom_id', $this);
        Validations::inRange('bathroom_id', 1, PHP_INT_MAX, $this);
        Validations::isIdFrom('bathroom_id', $this, Bathroom::class);

        Validations::notEmpty('image_name', $this);
        Validations::isString('image_name', $this);

        // 🔹 Validar tipo e tamanho da imagem (se existirem)
        if (isset($this->image_size) && $this->image_size > self::MAX_IMAGE_ACEPTED_SIZE) {
            $this->addError('image_name', 'A imagem excede o tamanho máximo permitido de 40 MB.');
        }

        if (isset($this->image_type) && !in_array($this->image_type, ['image/jpg', 'image/png', 'image/jpeg'])) {
            $this->addError('image_name', 'Formato de imagem inválido. Use JPG, PNG ou JPEG.');
        }
    }


    public function imageService(): Image
    {
        return new Image($this, '/bathrooms/images');
    }

    /**
     * @return BelongsTo<BathroomImage, Bathroom>
     */
    public function bathroom(): BelongsTo
    {
        return $this->belongsTo(Bathroom::class, 'bathroom_id');
    }
}
