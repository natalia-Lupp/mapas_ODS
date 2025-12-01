<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

abstract class IdCacheableModel extends Model
{
    /**
     * @var array<string, array<int, static |null> | null> $cache
     */
    protected static array $cache = [];

    /**
     * @return static | null
     */
    public static function findById(int $id): static|null
    {
        if (!isset(static::$cache[static::class])) {
            $cache[static::class] = [];
        }

        return isset(static::$cache[static::class][$id])
            ? static::$cache[static::class][$id]
            : (static::$cache[static::class][$id] = parent::findById($id));
    }
}
