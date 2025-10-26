<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Core\Database\Database;
use Core\Exceptions\ReferentialIntegrityException;
use Lib\Paginator;
use PDOException;

/**
 * @property int $id
 * @property int $n_floors
 * @property string $name
 */
class Building extends Model
{
    protected static string $table = 'buildings';
    protected static array $columns = [
      'n_floors',
      'name'
    ];
    /**
     * @var array<int, static |null> $cache
     */
    protected static array $cache = [];

    public function validates(): void
    {
        Validations::notEmpty('n_floors', $this);
        Validations::isInt('n_floors', $this);
        Validations::inRange('n_floors', 1, PHP_INT_MAX, $this);

        Validations::notEmpty('name', $this);
        Validations::isString('name', $this);
        Validations::inRangeLength('name', 1, 100, $this);
        Validations::uniquenessMutable('name', $this);
    }

    public function getBathroomPaginator(int $page, int $per_page, ?string $route): Paginator
    {
        return Bathroom::paginate(
            $page,
            $per_page,
            $route,
            ['building_id' => $this->id]
        );
    }

    public static function findById(int $id): static|null
    {
        return isset(self::$cache[$id])
        ? self::$cache[$id]
        : (self::$cache[$id] = parent::findById($id));
    }
    public function destroy(): bool
    {
        $table = static::$table;

        $sql = <<<SQL
            DELETE FROM {$table} WHERE id = :id;
        SQL;

        $pdo = Database::getDatabaseConn();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $this->id);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            throw new ReferentialIntegrityException(
                'Este prédio não pode ser deletado porque possui banheiros relacionados a ele.'
            );
        }
        return ($stmt->rowCount() != 0);
    }
}
