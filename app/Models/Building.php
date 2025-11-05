<?php

namespace App\Models;

use Core\Database\ActiveRecord\HasMany;
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
        Validations::match('name', "/[a-zA-Z0-9]{1,100}/", $this);
        Validations::uniquenessMutable('name', $this);
    }

    public function bathrooms(): HasMany
    {
        return $this->hasMany(Bathroom::class, 'building_id');
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


    public function countBathrooms(): int
    {
        $pdo = \Core\Database\Database::getDatabaseConn();
        $sql = "SELECT COUNT(*) as total FROM bathrooms WHERE building_id = :building_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':building_id', $this->id);
        $stmt->execute();

        $row = $stmt->fetch();
        return (int) $row['total'];
    }

    public function getBathroomsPerFloor(): int
    {
        $totalBaths = $this->countBathrooms();

        if ($this->n_floors > 0) {
            return intdiv($totalBaths, $this->n_floors);
        }

        return 0;
    }
}
