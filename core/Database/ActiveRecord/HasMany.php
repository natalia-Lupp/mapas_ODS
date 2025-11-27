<?php

namespace Core\Database\ActiveRecord;

use Lib\Paginator;
/**
 * @template-covariant M of Model
 * @template-covariant R of Model
 * @property M $model
 * @property class-string<R> $related
 * @property string $foreignKey
 */
class HasMany
{
    public function __construct(
        private Model $model,
        private string $related,
        private string $foreignKey
    ) {
    }

    /**
     * @return array<R>
     */
    public function get(): array
    {
        return $this->related::where([$this->foreignKey => $this->model->id]);
    }

    /**
     * @param array<string, mixed> $params
     * @return R
     */
    public function new(array $params = []): Model
    {
        $params[$this->foreignKey] = $this->model->id;

        return new $this->related($params);
    }
    /**
     * @return R
     */
    public function findById(int $id): ?Model
    {
        return $this->related::findBy(
            [
                $this->foreignKey => $this->model->id,
                'id' => $id,
            ]
        );
    }

    /**
     * @return array<R>
     * @param array<string, mixed>
     */
    public function findBy(array $params): ?Model
    {
      return $this->related::findBy(
        array_merge([$this->foreignKey => $this->model->id], $params)
      );
    }
    public function paginate(int $page = 1, int $per_page = 10, string $route = null): Paginator
    {
        return new Paginator(
            class: $this->related,
            page: $page,
            per_page: $per_page,
            table: $this->related::table(),
            attributes: $this->related::columns(),
            conditions: [$this->foreignKey => $this->model->id],
            route: $route
        );
    }
}
