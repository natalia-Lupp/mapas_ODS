<?php

$consumptions = $paginator->registers();
$json = array_map(
    fn ($obj) => [
      'name' => $obj->bathroomItem()->get()->type()->get()->name,
      'date' => $obj->date,
      'quantity' => $obj->quantity,
      'id' => $obj->id
    ],
    $consumptions
);
