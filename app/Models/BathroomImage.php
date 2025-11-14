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


    public function validates(): void
    {
        parent::validates();
        Validations::notEmpty('bathroom_id', $this);
        Validations::isInt('bathroom_id', $this);
        Validations::inRange('bathroom_id', 1, PHP_INT_MAX, $this);
        Validations::isIdFrom('bathroom_id', $this, Bathroom::class);
        // 🔹 Validar tipo e tamanho da imagem (se existirem)
    }


    public function imageService(): Image
    {
        // Carrega o banheiro relacionado
        $bathroom = $this->bathroom()->get();

        // Pega o id do prédio
        $buildingId = $bathroom->building_id;

        return new Image(
            $this,
            "bathrooms/{$buildingId}/{$this->bathroom_id}",
            [
                'extension' => ['jpg', 'jpeg', 'png'],
                'size' => 1024 * 3,
            ]
        );
    }


    /**
     * @return BelongsTo<BathroomImage, Bathroom>
     */
    public function bathroom(): BelongsTo
    {
        return $this->belongsTo(Bathroom::class, 'bathroom_id');
    }
}
