<?php

namespace App\Models;

use App\Models\BathroomImage;
use App\Models\BathroomItem;
use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use Core\Database\ActiveRecord\BelongsToMany;
use Lib\Validations;
use App\Models\IdCacheableModel;
use App\Services\BathroomItemService;

/**
 * @property int $id
 * @property int $floor
 * @property int $building_id
 */
class Bathroom extends IdCacheableModel
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

    /**
     * @return HasMany<Bathroom, BathroomImage>
     */
    public function images(): HasMany
    {
        return $this->hasMany(BathroomImage::class, 'bathroom_id');
    }

    /**
     * @return BelongsTo<Bathroom, Building>
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /**
     * @return BelongsToMany<Bathroom, BathroomItemType>
     */
    public function itemsType(): BelongsToMany
    {
        return $this->BelongsToMany(
            BathroomItemType::class,
            'bathroom_items',
            'bathroom_id',
            'bathroom_item_type_id'
        );
    }

    /**
     * @return HasMany<Bathroom, BathroomItem>
     */
    public function items(): HasMany
    {
        return $this->hasMany(BathroomItem::class, 'bathroom_id');
    }

    /**
     * @return HasMany<Bathroom, Consumption>
     */
    public function consumptions(): HasMany
    {
        return $this->hasMany(Consumption::class, 'bathroom_id');
    }

    public function itemService(): BathroomItemService
    {
        return new BathroomItemService($this);
    }

    public function deleteCascade(): void
    {
        //Excluir itens
        $items = $this->items();
        foreach ($items as $item) {
            $item->destroy();
        }

        //Excluir imagens
        $images = $this->images();

        $tempImage = new BathroomImage(['bathroom_id' => $this->id]);
        $imageService = $tempImage->imageService(); //rasteia os itens na pasta
        $storeDir = $imageService->getStoreDir();

        foreach ($images as $img) {
            $img->imageService()->deleteImage();
        }

        $imageService->deleteStoreDirIfEmpty($storeDir);

        //Excluir o banheiro
        $this->destroy();
    }

    public function lengthOfItem(BathroomItemType $itemType): int
    {
        foreach ($this->items as $item) {
            if ($item->bathroom_item_type_id === $itemType->id) {
                return $item->quantity;
            }
        }
        return 0;
    }
}
