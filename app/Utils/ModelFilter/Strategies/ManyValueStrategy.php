<?php

namespace App\Utils\ModelFilter\Strategies;

use Illuminate\Database\Eloquent\Builder;

class ManyValueStrategy extends BaseFilterStrategy
{
    private string $field;
    private array $values;

    public function __construct(string $field, array $values, bool $isOr, bool $negate)
    {
        parent::__construct($isOr, $negate);
        $this->field = $field;
        $this->values = $values;
    }

    public function apply(Builder $query): Builder
    {
        if ($this->isOr) {
            return $this->applyOrCondition($query);
        }
        return $this->applyAndCondition($query);
    }

    private function applyOrCondition(Builder $query): Builder
    {
        $containsWildcard = array_reduce($this->values, fn($carry, $item) => $carry || strpos($item, '%') !== false, false);

        if (!$containsWildcard && !$this->negate) {
            return $query->whereIn($this->field, $this->values);
        }

        if (!$containsWildcard && $this->negate) {
            return $query->whereNotIn($this->field, $this->values);
        }

        return $query->where(function ($subQuery) {
            foreach ($this->values as $value) {
                $operator = $this->getOperator($value);
                $subQuery->orWhere($this->field, $operator, $value);
            }
        });
    }

    private function applyAndCondition(Builder $query): Builder
    {
        foreach ($this->values as $value) {
            $operator = $this->getOperator($value);
            $processedValue = $this->negate ? $value : $this->addWildcards($value);
            $query->where($this->field, $operator, $processedValue);
        }
        return $query;
    }

    private function addWildcards(string $value): string
    {
        return strpos($value, '%') === false ? "%$value%" : $value;
    }
}