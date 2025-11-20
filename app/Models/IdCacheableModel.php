<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

abstract class IdCacheableModel extends Model
{

    /**
     * @var array<int, static |null> $cache
     */
    protected static array $cache = [];

    public static function findById(int $id): static|null
    {
        return isset(self::$cache[$id])
            ? self::$cache[$id]
            : (self::$cache[$id] = parent::findById($id));
    }
}
