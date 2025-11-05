<?php

namespace App\Models;

use App\Services\Image;
use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property ?string $image_url
 * @property int $bathroom_id
 */
class BathroomImage extends Model
{
    protected static string $table = 'bathroom_images';
    protected static array $columns = [
        'image_name',
        'bathroom_id'
    ];

    public function bathroom(): BelongsTo
    {
        return $this->belongsTo(Bathroom::class, 'bathroom_id');
    }

    public function imageService(): Image
    {
        return new Image(
            model: $this,
            validations: [
                'extension' => ['jpg', 'jpeg', 'png'],
                'size' => (1024 * 3)
            ],
            storeDir: "bathrooms/" . $this->bathroom_id
        );
    }
}
