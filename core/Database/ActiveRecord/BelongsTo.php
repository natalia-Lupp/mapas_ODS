<?php

namespace Core\Database\ActiveRecord;

/**
 * @template-covariant M of Model
 * @template-covariant R of Model
 * @property M $model
 * @property class-string<R> $related
 * @property string $foreignKey
 */
class BelongsTo
{
    public function __construct(
        private Model $model,
        private string $related,
        private string $foreignKey
    ) {
    }
    /**
     * @return ?R
     */
    public function get(): ?Model
    {
        $attribute = $this->foreignKey;
        return $this->related::findBy(['id' => $this->model->$attribute]);
    }
}
