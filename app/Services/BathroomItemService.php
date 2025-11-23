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
        if (empty($this->items)) {
            $this->items[] = $this->bathroom->items()
            ->new([
            'bathroom_item_type_id' => 1,
            'quantity' => $params['taps'] ?? 0,
            ]);
            $this->items[1]->save();
            $this->items[] = $this->bathroom->items()
            ->new([
              'bathroom_item_type_id' => 2,
              'quantity' => $params['toilets'] ?? 0,
            ]);
            $this->items[2]->save();
            return true;
        }
        return $this->update($params);
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
