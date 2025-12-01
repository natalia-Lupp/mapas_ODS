<?php

namespace App\Services;

use App\Models\Bathroom;
use App\Models\BathroomItem;
use App\Models\BathroomItemType;
use Core\Database\Database;

/**
 * @property Bathroom $bathroom
 */
class BathroomItemService
{
    /**
     * @var array<int, BathroomItem> $items
     */
    protected array $items = [];

    public function __construct(private Bathroom $bathroom)
    {
    }

    public function initItems(): void
    {
    }

    /**
     * @param array<string, int> $params
     * @return bool
     */
    public function create(array $params = []): bool
    {
        return $this->update($params);
    }

    /**
     * @return array<int, BathroomItem>
     */
    public function getItems(): array
    {
        return $this->bathroom->items()->get();
    }

    /**
     * @param array<string, int> $params
     * @return bool
     */
    public function update(array $params): bool
    {

        Database::startTansaction();
        foreach ($params as $item_id => $quantity) {
            $type = BathroomItemType::findById((int)$item_id);

            if (isset($type)) {
                $item = $this->bathroom->items()
                    ->findBy([
                        'bathroom_item_type_id' => $item_id,
                    ]);

                if (isset($item)) {
                    if ($quantity <= 0) {
                        $item->destroy();
                        continue;
                    }
                    $item->quantity = $quantity;

                    if (!$item->save()) {
                        Database::rollBack();
                        return false;
                    }
                } else {
                    if ($quantity > 0) {
                        $item = $this->bathroom->items()
                            ->new([
                                'bathroom_item_type_id' => $item_id,
                                'quantity' => $quantity,
                        ]);
                        if (!$item->save()) {
                            Database::rollBack();
                            return false;
                        }
                    }
                }
            }
        }
        Database::commit();
        return true;
    }
}
