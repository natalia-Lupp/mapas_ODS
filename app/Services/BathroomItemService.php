<?php

namespace App\Services;

use App\Models\Bathroom;
use App\Models\BathroomItem;
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
        /**
         * @var array<int, BathroomItem> $items
         */
        $items =   $bathroom->items()->get();
        $this->items = $items;
    }

    public function initItems(): void
    {
        if (empty($this->items)) {
            $this->create();
        }
    }

    /**
     * @param array<string, int> $params
     * @return bool
     */
    public function create(array $params = []): bool
    {
        foreach ($params as $item_id => $quantity) {
            if ($quantity <= 0) {
                continue;
            }
            $item = $this->bathroom->items()
                ->new([
                    'bathroom_item_type_id' => $item_id,
                    'quantity' => $quantity,
                ]);
            $item->save();
            $this->items[] = $item;
        }
        return true;
    }

    /**
     * @return array<int, BathroomItem>
     */
    public function getItems(): array
    {
        if (empty($this->items)) {
            $this->initItems();
        }
        return $this->items;
    }

    /**
     * @param array<string, int> $params
     * @return bool
     */
    public function update(array $params): bool
    {
        foreach ($params as $item_id => $quantity) {
            $item = $this->bathroom->items()
                ->findBy(['bathroom_item_type_id' => $item_id]);

            // if a quantity is zero, e o item existe, deleta.
            // if a quantity for diferente de zero, e o item existe, atualiza.
            // if a quantidade for diferente de zero, e o item n existe, cria.



            if ($quantity <= 0) {
                continue;
            }
            $item = $this->bathroom->items()
                ->new([
                    'bathroom_item_type_id' => $item_id,
                    'quantity' => $quantity,
                ]);
            $item->save();
            $this->items[] = $item;
        }
        return true;



        $this->initItems();
        $toilets = $this->items[1];
        $taps = $this->items[0];

        Database::startTansaction();

        $toilets->quantity = $params['toilets'];
        $taps->quantity = $params['taps'];

        if ($taps->save() && $toilets->save()) {
            Database::commit();
            return true;
        }

        Database::rollBack();
        return false;
    }
}
