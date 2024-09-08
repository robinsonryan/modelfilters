<?php

namespace App\Utils\ModelFilter\Strategies;

use Illuminate\Database\Eloquent\Builder;

class ManyFieldStrategy extends BaseFilterStrategy
{
    private array $fields;
    private string $value;

    public function __construct(array $fields, string $value, bool $isOr, bool $negate)
    {
        parent::__construct($isOr, $negate);
        $this->fields = $fields;
        $this->value = $value;
    }

    public function apply(Builder $query): Builder
    {
        $operator = $this->getOperator($this->value);

        if ($this->isOr) {
            return $query->where(function ($subQuery) use ($operator) {
                foreach ($this->fields as $field) {
                    $subQuery->orWhere($field, $operator, $this->value);
                }
            });
        }

        foreach ($this->fields as $field) {
            $query->where($field, $operator, $this->value);
        }
        return $query;
    }
}